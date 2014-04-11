<?php
/** Form for logging into the system
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class LoginForm extends Pas_Form {

public function init() {
       
	$this->setName('login');
	
	$username = $this->addElement('text', 'username',array('label' => 'Username: '));
	$username = $this->getElement('username')
	->setRequired(true)
	->addFilters(array('StringTrim', 'StripTags'))
	->addValidator('Authorise')
	->setAttrib('size','20');

	$password = $this->addElement('password', 'password',array('label' => 'Password: '));
	$password = $this->getElement('password')
	->addValidator('StringLength', true, array(3))
	->setRequired(true)
	->setAttrib('size','20')
	->addFilters(array('StringTrim', 'StripTags'));
	$password->getValidator('StringLength')
	->setMessage('Your password is too short');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
	->setTimeout(4800);
	$this->addElement($hash);

	$submit = $this->addElement('submit', 'submit' , array('label' => 'Login...'));

	$this->addDisplayGroup(array('username','password','submit'), 'details');

//	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Login: ');
	
	parent::init();
    }
}