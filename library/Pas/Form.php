<?php

/**
* Extension of Zend form for PAS project
*
* @category   Pas
* @package    Form
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Form extends EasyBib_Form {

    public $_salt;
    public $_pubKey;
    public $_privateKey;

    public $_role;
    
    protected $_view;
    
	public function init()  {
	$this->_privateKey = Zend_Registry::get('config')->webservice->recaptcha->privatekey;
	$this->_pubKey = Zend_Registry::get('config')->webservice->recaptcha->pubkey;
	$this->_salt = Zend_Registry::get('config')->form->salt;
	EasyBib_Form_Decorator::setFormDecorator($this, EasyBib_Form_Decorator::BOOTSTRAP, 'submit', 'cancel');
	
    }


    public function __construct() {
    $this->addPrefixPath('Pas_Form_Element', 'Pas/Form/Element', 'element');
	$this->addElementPrefixPath('Pas_Filter','Pas/Filter/','filter');
	$this->addElementPrefixPath('Pas_Validate', 'Pas/Validate/', 'validate');
	$this->setAttrib('class', 'form-horizontal');
	$this->setAttrib('accept-charset', 'UTF-8');
	$this->clearDecorators();
	$this->_view = Zend_Layout::getMvcInstance()->getView();
	$this->_view->jQuery()->addJavascriptFile('/js/select2.min.js', $type='text/javascript');
	$this->_view->jQuery()->addJavascriptFile('/js/selectPrettify.js', $type='text/javascript');
	$this->_view->headLink()->appendStylesheet('/css/select2.css', $type='screen');
	$person = new Pas_User_Details();
	$details = $person->getPerson();
	if($details){
	$this->_role = $details->role;
	} else {
		$this->_role = 'public';
	}
	
	parent::__construct();
	}

	


}
