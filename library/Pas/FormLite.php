<?php

/**
* Extension of Zend form for PAS project
*
* @category   Pas
* @package    Form
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_FormLite extends EasyBib_Form {

    public $_salt;
    public $_pubKey;
    public $_privateKey;

    public $_role;
    
	public function init()  {
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
