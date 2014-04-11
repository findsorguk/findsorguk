<?php

/** Form for setting up and editing personal profile
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ProfileForm extends Pas_Form
{
    protected $_actionUrl;

    protected $_copyright = NULL;

    public function __construct($actionUrl = null, $options=null) {
        parent::__construct($options);
        $this->setActionUrl($actionUrl);
        $this->init();
    }

    public function setActionUrl($actionUrl) {
        $this->_actionUrl = $actionUrl;
        return $this;
    }

    public function init()
    {
        $required = true;
        $copyrights = new Copyrights();
		$copy = $copyrights->getStyles();
        $this->setAction($this->_actionUrl)
             ->setMethod('post')
             ->setAttrib('id', 'accountform');

		$username = $this->addElement('text','username',array('label' => 'Username:'))
			->username;
                $username = $this->getElement('username');
		$username->Disabled = true;
		$username->addFilters(array('StringTrim','StripTags'));


        $firstName = $this->addElement('text', 'first_name',
            array('label' => 'First Name: ', 'size' => '30'))->first_name;
        $firstName = $this->getElement('first_name');
        $firstName->setRequired(true)
			->addFilters(array('StringTrim','StripTags'))
			->addErrorMessage('You must enter a firstname');

        $lastName = $this->addElement('text', 'last_name',
            array('label' => 'Last Name: ', 'size' => '30'))->last_name;
        $lastName = $this->getElement('last_name');
        $lastName->setRequired(true)
			->addFilters(array('StringTrim','StripTags'))
			->addErrorMessage('You must enter a surname');

        $fullname = $this->addElement('text', 'fullname',
            array('label' => 'Preferred Name: ', 'size' => '30'))->fullname;
        $fullname = $this->getElement('fullname');
        $fullname->setRequired(true)
			->addFilters(array('StringTrim','StripTags'))
			->addErrorMessage('You must enter your preferred name');

        $email = $this->addElement('text', 'email',array('label' => 'Email Address', 'size' => '30'))
			->email;
        $email = $this->getElement('email');
        $email->setRequired(true)
			->addErrorMessage('Please enter a valid address!')
			->addFilters(array('StringTrim','StripTags','StringToLower'))
			->addValidator('EmailAddress',false,array('mx' => true));

	$password = $this->addElement('password', 'password',array('label' => 'Change password: ', 'size' => '30'))->password;
        $password = $this->getElement('password');
        $password->addFilters(array('StringTrim','StripTags'))
			  ->setRequired(false);

        $copyright = $this->addElement('select','copyright',array('label' => 'Default copyright: '))
			->copyright;
        $copyright = $this->getElement('copyright');
        $copyright->setRequired(TRUE);
        $copyright->addMultiOptions(array(NULL => 'Select a licence holder',
        	'Valid copyrights' => $copy))
			->addValidator('InArray', false, array(array_keys($copy)));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Save details');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	$this->addElements(array($hash,$submit));
	$this->addDisplayGroup(array(
	'username','first_name','last_name',
	'fullname','email','password',
	'copyright'), 'userdetails');

	$this->setLegend('Edit your account and profile details: ');

	$this->addDisplayGroup(array('submit'),'buttons');
	parent::init();
    }
}