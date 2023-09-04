<?php

namespace Logging;

use Exception;
use Zend_Auth;
use Zend_Exception;
use Zend_Log;
use Zend_Log_Exception;
use Zend_Log_Writer_Stream;
use Zend_Registry;

class Logging
{
    protected const INFO = 6; // Informational messages
    protected Zend_Auth $zendAuth;
    protected array $config;
    protected string $directory;

    /**
     * @throws Zend_Exception
     * @throws Exception
     */
    public function __construct()
    {
        $this->zendAuth = Zend_Auth::getInstance();
        $this->config = (Zend_Registry::get('config')->logger)->toArray();
        $this->directory = rtrim(($this->config['basePath'] ?? '../app/logs/') . date('Y'), "/") . "/";
        $this->createDirectory();
    }

    /** Create the log directory if it does not exist. Only let owner and group to view, edit, and execute file.
     * @throws Exception
     */
    protected function createDirectory()
    {
        if (!is_dir($this->directory)) {
            if (!mkdir($this->directory, 0770, true)) {
                throw new Exception('Cannot create directory for logs');
            }
        }
    }

    /** Log requests to the website, including the requesters username and role if available.
     * Allow for custom messages appended to end of log.
     * @throws Zend_Log_Exception
     */
    public function logRequestsToWebsite(int $severity, string ...$messages)
    {
        if (!($this->config['enableRequestLogs'] ?? 0)) {
            return;
        }

        $messages = array_merge(
            array(
                "INFO",
                str_pad($_SERVER['REQUEST_METHOD'], 4, " "),
                str_pad($_SERVER['REMOTE_ADDR'], 15, " "),
                str_pad($this->getRole(), 8, " "),
                $this->getUsername(),
                $_SERVER['REDIRECT_URL'],
                $_SERVER['HTTP_USER_AGENT']
            ),
            $messages
        );

        if ($_SERVER['REDIRECT_URL'] != '/ajax/keepalive') {
            $this->writeLog($severity, "request-log-" . date('m-d'), $messages);
        }
    }

    protected function getRole(): string
    {
        if ($this->zendAuth->hasIdentity()) {
            return $this->zendAuth->getIdentity()->role;
        }
        return "public";
    }

    protected function getUsername(): string
    {
        if ($this->zendAuth->hasIdentity()) {
            return $this->zendAuth->getIdentity()->username;
        }
        return "";
    }

    /** Write to log file, taking an array of messages that are joined by a separator (default a pipe).
     * @throws Zend_Log_Exception
     */
    public function writeLog(int $severity, string $logName, array $messages, string $separator = ' | ')
    {
        if ($severity > 7) {
            $messages[] = "Invalid severity, max value is 7 (debug). Please look at Zend_Log class for context.";
            $severity = self::INFO; //Default to info
        }

        $logger = new Zend_Log(new Zend_Log_Writer_Stream($this->directory . $logName . date('m-d') . '.log'));
        $logger->log(implode($separator, $messages), $severity);
    }
}
