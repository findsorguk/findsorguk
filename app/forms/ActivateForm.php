<?php

/**
* Form for changing a user's password
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ActivateForm extends Pas_Form
{

    public function __construct($actionUrl = null, $options=null) {
        parent::__construct($options);
        $this->init();
    }


    public function init() {

    	$username = new Zend_Form_Element_Text('username');
    	$username->setLabel('Your username');
    	$username->setRequired(true)
    		->addFilters(array('StringTrim', 'StripTags'))
    		->addValidator('Db_RecordExists', false, array('table' => 'users',
                                                           'field' => 'username'));
    	
    	
    	$activationKey = new Zend_Form_Element_Text('activationKey');
		$activationKey->setLabel('Your activation key');
		$activationKey->setDescription('Your key was sent in your activation email')->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('Db_RecordExists', false, array('table' => 'users',
                                                           'field' => 'activationKey'));
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Your email address');
		$email->setRequired(true)
    		->addValidator('Db_RecordExists', false, array('table' => 'users',
                                                           'field' => 'email'))
    		->addValidator('EmailAddress',false, array('mx' => true));

	    // identical field validator with custom messages
	   	$hash = new Zend_Form_Element_Hash('csrf');
		$hash->setValue($this->_salt)->setTimeout(480);

		       //Submit button
    $submit = new Zend_Form_Element_Submit('submit');
   $submit->setLabel('Activate me!');

    $this->addElement($submit);
		
		$this->addElements(array( $username, $activationKey, $email,  $hash));


		$this->addDisplayGroup(array('username','email','activationKey'), 'userdetails');
$this->addDisplayGroup(array('submit'), 'buttons');

		$this->setLegend('Enter details: ');

    	parent::init();
	}

}