<?php

/**
 * A validation class for checking for whether a RRC id provided is valid
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Validate
 * @version 1
 * @see Zend_Validate_Abstract
 * @use Pas_Curl
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
abstract class Pas_Validate_NumismaticsAbstract extends Zend_Validate_Abstract
{

    /** The not valid message container
     *
     */
    protected const NOT_VALID = 'notValid';

    /** The http error message container
     *
     */
    protected const HTTP_ERROR = 'httpError';

    /** Validation failure message template definitions
     * @access protected
     * @var array
     */
    protected $_messageTemplates;

    /** The error message
     * @access protected
     * @var string
     */
    protected $_errorMessage;

    /** The error message email subject
     * @access protected
     * @var string
     */
    protected $_errorMessageSubject;

    /** The error message
     * @access protected
     * @var string
     */
    protected $_url;

    /** Check if the value is valid
     * @access @access public
     * @param string $value
     * @return boolean
     */
    public function isValid($value)
    {
        $type = $this->getType($value);
        if (!$type) {
            $this->_error($this->_errorMessage);
            return false;
        }
        return true;
    }

    /**Send Numismatics error email
     * @param $error
     * @param string $type
     * @return void
     * @throws Zend_Mail_Exception|Zend_Exception
     */
    public function sendErrorEmail($error)
    {
        $mailer = new Pas_Controller_Action_Helper_Mailer();
        $mailer->init();
        $adminEmail = array_map(function ($email, $name) { return ['email' => $email, 'name' => $name]; },
            Zend_Registry::get('config')->admin->email->toArray(),
            Zend_Registry::get('config')->admin->name->toArray()
        );
        $mailer->direct(array('error' => $this->_errorMessageSubject),
            'numismaticsError',
            $adminEmail
        );
    }

    /** Get whether the ID exists in the CORR system
     * @access public
     * @param string $value
     * @return string
     */
    public function getType($id)
    {
        $key = md5($id . get_class($this));
        $cache = Zend_Registry::get('cache');

        if (($cache->load($key)) !== false) {
            return true;
        } else {
            // cache missed
            $curl = new Pas_Curl();
            $curl->setUri($this->_url . $id);

            try {
                $curl->getRequest();
            } catch (Exception $e) {
                $this->_errorMessage = self::HTTP_ERROR;
                $this->sendErrorEmail($e);
                return false;
            }

            $response = $curl->getResponseCode();
            if ($curl->getResponseCode() == '200') {
                $cache->save($response);
                return true;
            } elseif (preg_match('/5[0-9][0-9]/', $response)) {
                $this->_errorMessage = self::HTTP_ERROR;
            } else {
                $this->_errorMessage = self::NOT_VALID;
            }

            $this->sendErrorEmail($response);
            return false;
        }
    }
}
