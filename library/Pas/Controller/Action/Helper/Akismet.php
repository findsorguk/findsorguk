<?php
/** An action helper for determining whether a submitted comment is termed as
 * spam by the akismet service.
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $data = $this->_helper->akismet($form->getValues());
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 * @license http://URL name
 * @uses \Zend_Service_Akismet
 * @example /app/modules/database/controllers/ArtefactsController.php
 * 
 */

class Pas_Controller_Action_Helper_Akismet extends Zend_Controller_Action_Helper_Abstract {

    /** The spam constant
     * 
     */
    CONST SPAM = 'spam';

    /** The not spam constant
     * 
     */
    CONST NOTSPAM = 'notspam';
    
    /** The akismet key
     * @access protected
     * @var string 
     */
    protected $_akismetKey;

    /** The akismet service
     * @access protected
     * @var \Zend_Service_Akismet
     */
    protected $_akismet;

    /** The additional fields to use
     * @access protected
     * @var array
     */
    protected $_additionalFields = array('user_ip', 'user_agent',
        'content_type', 'comment_author',
        'comment_author_email', 'comment_content');


    /** Construct the akismet services
     * @access public
     */
    public function __construct(){
        $config = Zend_Registry::get('config');
        $baseurl = Zend_Registry::get('siteurl');
        $this->_akismetKey = $config->webservice->akismet->apikey;
        $this->_akismet = new Zend_Service_Akismet(
                $this->_akismetKey,
                $baseurl
                );
    }

    /** Check the data for spam or not
     * @access public
     * @param array $data
     * @return type
     */
    public function direct(array $data){
        $cleanData = $this->_checkFields($data);
        if($this->_akismet->isSpam($cleanData)){
            $cleanData['commentStatus'] = self::SPAM;
        } else {
         $cleanData['commentStatus'] = self::NOTSPAM;
        }
        return $cleanData;
    }

    /** Verify the akismet key
     * @access public
     * @return boolean
     * @throws Zend_Exception
     */
    public function verifyKey(){
        if($this->_akismet->verifyKey($this->_akismetKey)){
            return true;
        } else {
            throw new Zend_Exception('Akismet key failed validation', 500);
        }
    }

    /** Check fields supplied
     * @access public
     * @param array $data
     * @return type
     */
    public function _checkFields(array $data){
        if(!array_key_exists('user_ip', $data)){
            $data['user_ip'] = Zend_Controller_Front::getInstance()->getRequest()->getClientIp();
        }
        if(!array_key_exists('user_agent', $data)){
            $useragent = new Zend_Http_UserAgent();
            $data['user_agent'] = $useragent->getUserAgent();
        }
        foreach ($data as $k => $v){
            if(!in_array($k, $this->_additionalFields)){
                unset($data[$k]);
            }
        }
        return $data;
    }
}