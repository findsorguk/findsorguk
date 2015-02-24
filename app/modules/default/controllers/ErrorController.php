<?php

/** A controller for dealing with exceptions and errors
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 2
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 */
class ErrorController extends Pas_Controller_Action_Admin
{

    /** Whether email can be sent - default  true
     * @access protected
     * @var boolean
     */
    protected $_email = true;

    /** The log
     *
     */
    protected $_log;

    /** Get email config entry
     * @access public
     * @return boolean
     */
    public function getEmail()
    {
        $email = $this->getInvokeArg('bootstrap')->getOption('email');
        if (!$email) {
            $this->_email = false;
        }
        return $this->_email;
    }

    /** Set up values
     * @access public
     */
    public function init()
    {
        $this->_log = Zend_Registry::get('log');
        $this->_helper->_acl->allow(null);
        Zend_Layout::getMvcInstance()->setLayout("error");
    }


    /** Work out who created the error
     * @access public
     * @return string
     */
    public function whois()
    {
        $user = new Pas_User_Details();
        if (is_null($user->getPerson())) {
            $string = 'Public user';
        } else {
            $name = $user->getPerson()->fullname;
            $account = $user->getPerson()->username;
            $string = $name . ' with the account username of ' . $account;
        }
        return $string;
    }

    /** Set up the mailer data
     * @return array
     */
    protected function _mailData()
    {
        $details = array();
        $details['username'] = $this->whois();
        $errors = $this->getParam('error_handler');
        $details['file'] = get_class($errors['exception']);
        $server = Zend_Controller_Front::getInstance()->getRequest();
        $details['ip'] = $server->getClientIp();
//        $details['method'] = $server->getRe
//        $details['agent'] = $server->get('HTTP_USER_AGENT');
//        $details['referrer'] = $server->get('HTTP_REFERER');
        $details['url'] = $this->view->curUrl();
        if ($errors) {
            $details['exception'] = $errors->exception->getMessage();
            $details['type'] = $errors['type'];
            $details['code'] = $errors['exception']->getCode();
            $details['exceptionDetails'] = $errors->exception;
        }
        return $details;
    }


    /** Send emails
     * @access public
     * @return \Zend_Mail
     */
    public function sendEmail()
    {
        if ($this->getEmail()) {
            $to[] = array(
                'name' => 'Daniel Pett',
                'email' => 'danielpett@gmail.com'
            );
            $cc[] = array(
                'name' => 'Mary Chester-Kadwell',
                'email' => 'mchester-kadwell@britishmuseum.org'
            );
            $from[] = array(
                'name' => 'The Portable Antiquities Server',
                'email' => 'no-reply@finds.org.uk'
            );
            $assignData = array_merge($this->_mailData(), $to['0']);
            return $this->_helper->mailer($assignData, 'serverError', $to, $cc, $from, null, null);
        }
    }


    /** Get the log
     * @access public
     * @return boolean
     */
    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    /** The index action
     * @access public
     */
    public function indexAction()
    {
        $this->getFlash()->addMessage('You cannot access the root page for errors');
        $this->getResponse()->setHttpResponseCode(301)->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $this->redirect('/');
    }

    /** The error action
     * @access public
     */
    public function errorAction()
    {
        // Ensure the default view suffix is used so we always return good
        // content
        $this->_helper->viewRenderer->setViewSuffix('phtml');
        // Grab the error object from the request
        $errors = $this->getParam('error_handler');
        if ($errors) {
            switch ($errors->type) {
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                    // 404 error -- controller or action not found
                    $this->getResponse()->setHttpResponseCode(404);
                    if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER) {

                        $this->view->code = 404;
                        $this->view->message = sprintf(
                            'Unable to find page',
                            $errors->request->getActionName(),
                            $errors->request->getControllerName(),
                            $errors->request->getModuleName()
                        );
                        $this->renderScript('error/error.phtml');
                        $priority = Zend_Log::NOTICE;
                        $log = $this->getLog();
                        if ($log) {
                            $log->log($this->view->message . ' ' . $errors->exception, $priority, $errors->exception);
                        }
                    }
                    break;
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                    // 404 error -- controller or action not found
                    $this->getResponse()->setHttpResponseCode(404);
                    if ($errors->type == Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION) {

                        $this->view->code = 404;
                        $this->view->message = sprintf(
                            'Unable to find page',
                            $errors->request->getActionName(),
                            $errors->request->getControllerName(),
                            $errors->request->getModuleName()
                        );
                        $this->renderScript('error/error.phtml');
                        $priority = Zend_Log::NOTICE;
                        $log = $this->getLog();
                        if ($log) {
                            $log->log($this->view->message . ' ' . $errors->exception, $priority, $errors->exception);
                        }
                    }
                    break;
                case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                    switch (get_class($errors['exception'])) {
                        case 'Pas_Exception_NotAuthorised' :
                            $this->getResponse()->setHttpResponseCode(401);
                            $this->view->message = 'This record falls outside your access levels. ';
                            $this->view->code = 401;
                            $this->sendEmail();
                            break;
                        case 'Pas_Exception_AccountProblem' :
                            $this->getResponse()->setHttpResponseCode(412);
                            $this->view->message = 'You cannot record objects';
                            $this->view->code = 412;
                            break;
                        case 'Solarium_Exception':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->message = 'Search index has not returned results this time';
                            $this->view->code = 500;
                            break;
                        case 'Pas_Exception':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->message = 'There has been an internal server error!';
                            $this->view->code = 500;
                            break;
                        case 'Pas_Exception_Param':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->message = 'The url you used is missing a parameter';
                            $this->view->code = 500;
                            break;
                        case 'Zend_Db_Statement_Exception' :
                            $this->getResponse()->setHttpResponseCode(503);
                            $this->view->code = 503;
                            $this->view->message = 'A SQL error has been found';
                            $this->sendEmail();
                            break;
                        case 'Zend_Db_Adapter_Exception':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = 'Server has gone away (usually being restarted or processes killed.)';
                            $this->sendEmail();
                            break;
                        case 'Zend_Db_Table_Exception':
                            if (preg_match("/primary/i", $errors->exception->getMessage())) {
                                $cache = Zend_Registry::get('cache');
                                $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
                                $this->getResponse()->setHttpResponseCode(500);
                                $this->view->message = 'Cache file needs a clean! Please try again.';
                                $this->view->code = 500;
                                $this->sendEmail();
                            }
                            break;
                        case 'Zend_Db_Statement_Mysqli_Exception':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = 'Server has gone away';
                            $this->sendEmail();
                            break;
                        case 'PDOException':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = 'PDO exception has been caught';
                            $this->sendEmail();
                            break;
                        case 'Pas_Solr_Exception':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = 'The search handler has an error';
                            break;
                        case 'Solarium_Client_HttpException':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = 'Search engine error: The server is not responding, but will be back shortly';
                            break;
                        case 'Zend_Loader_PluginLoader_Exception':
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = 'Plugin not found';
                            $this->sendEmail();
                            break;
                        case 'Zend_View_Exception' :
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = 'Rendering of view error.';
                            $this->sendEmail();
                            break;
                        default:
                            $this->getResponse()->setHttpResponseCode(500);
                            $this->view->code = 500;
                            $this->view->message = $errors->exception->getMessage();
                            break;
                    }

            }
            if ($errors->exception and $errors->exception instanceof Zend_Db_Exception) {
                $this->view->message = $errors->exception->getMessage();
                try {
                    if ($errors->exception->getPrevious() and $errors->exception->getPrevious() instanceof PDOException) {
                        $e = $errors->exception->getPrevious();
                    }
                } catch (PDOException $e) {

                }
            }
            // pass the actual exception object to the view
            $this->view->exception = $errors->exception;
            // pass the request to the view
            $this->view->request = $errors->request;
        } else {
            $this->view->code = 500;
            $this->view->message = 'An error has occurred.';
        }
    }

    /** Not authorised action
     * @access public
     */
    public function notauthorisedAction()
    {
        $this->getResponse()->setHttpResponseCode(401);
        $this->view->message = 'You are not authorised to view this resource';
        $this->view->code = 401;
        $this->renderScript('error/error.phtml');

    }

    /** Account problems
     * @access public
     */
    public function accountproblemAction()
    {
        $this->getResponse()->setHttpResponseCode(412);
        $this->view->message = 'There is a problem with your account';
        $this->view->code = 412;
    }

    /** Database down action
     *
     */
    public function databasedownAction()
    {
        $this->getResponse()->setHttpResponseCode(503);
        $this->view->message = 'The system is currently offline';
        $this->view->code = 503;
        $this->renderScript('error/error.phtml');
    }

    /** Account connection problem
     *
     */
    public function accountconnectionAction()
    {
        $this->getResponse()->setHttpResponseCode(412);
        $this->view->message = 'There is a problem with your account';
        $this->view->code = 412;
        $this->sendEmail();
    }

    /** Down time action
     *
     */
    public function downtimeAction()
    {
        $this->getResponse()->setHttpResponseCode(503);
        $this->view->message = 'The system is currently offline';
        $this->view->code = 503;
    }
}