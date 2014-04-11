<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Katiebear
 */

class Pas_Controller_Action_Helper_Akismet
    extends Zend_Controller_Action_Helper_Abstract {

    CONST SPAM = 'spam';

    CONST NOTSPAM = 'notspam';

    protected $_config;

    protected $_baseurl;

    protected $_akismetKey;

    protected $_akismet;

    protected $_additionalFields = array('user_ip', 'user_agent',
        'content_type', 'comment_author',
        'comment_author_email', 'comment_content');


    public function __construct(){
        $this->_config = Zend_Registry::get('config');
        $this->_baseurl = Zend_Registry::get('siteurl');
        $this->_akismetKey = $this->_config->webservice->akismet->apikey;
        $this->_akismet = new Zend_Service_Akismet($this->_akismetKey,
               $this->_baseurl);
//        $this->verifyKey($this->_akismetKey);
    }

    public function direct($data){
    $cleanData = $this->_checkFields($data);

    if($this->_akismet->isSpam($cleanData)){
    $cleanData['commentStatus'] = self::SPAM;
    } else {
    $cleanData['commentStatus'] = self::NOTSPAM;
    }
    return $cleanData;
    }


    public function verifyKey(){
        if($this->_akismet->verifyKey($this->_akismetKey)){
            return true;
        } else {
            throw new Pas_Exception_BadJuJu('Akismet key failed validation');
        }
    }


    public function _checkFields($data){
        if(!array_key_exists('user_ip', $data)){
            $data['user_ip'] = Zend_Controller_Front::getInstance()->getRequest()->getClientIp();
        }
        if(!array_key_exists('user_agent', $data)){
            $useragent = new Zend_Http_UserAgent();
            $data['user_agent'] = $useragent->getUserAgent();
        }
        foreach ($data as $k => $v){
            if(!in_array($k,$this->_additionalFields)){
                unset($data[$k]);
            }
        }

        return $data;
    }
}