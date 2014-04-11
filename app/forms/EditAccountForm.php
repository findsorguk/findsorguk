<?php
/** Form for editing a user's account details
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class EditAccountForm extends Pas_Form
{
    protected $_actionUrl;

    public function __construct($actionUrl = null, $options=null)
    {
        parent::__construct($options);
        $this->setActionUrl($actionUrl);
        $this->init();
    }

    public function setActionUrl($actionUrl) {
        $this->_actionUrl = $actionUrl;
        return $this;
    }

    public function init() {
        $roles = new Roles();
		$role_options = $roles->getRoles();

        $inst = new Institutions();
		$inst_options = $inst->getInsts();

        $this->setAction($this->_actionUrl)
             ->setMethod('post')
             ->setAttrib('id', 'accountform');

        $username = $this->addElement('text','username',array('label' => 'Username: '))->username;

        $username->addFilters(array('StripTags', 'StringTrim'))->setRequired(true);


        $firstName = $this->addElement('text', 'first_name',
            array('label' => 'First Name', 'size' => '30'))->first_name;
        $firstName->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->addErrorMessage('You must enter a firstname');

        $lastName = $this->addElement('text', 'last_name',
            array('label' => 'Last Name', 'size' => '30'))
		->last_name;
        $lastName->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->addErrorMessage('You must enter a surname');

		$preferred_name = $this->addElement('text', 'preferred_name',
		array('label' => 'Preferred Name: ', 'size' => '30'))
		->preferred_name;
        $preferred_name->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->addErrorMessage('You must enter your preferred name');

        $fullname = $this->addElement('text', 'fullname',
		array('label' => 'Full name: ', 'size' => '30'))
		->fullname;
        $fullname->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->addErrorMessage('You must enter your preferred name');

        $email = $this->addElement('text', 'email',array('label' => 'Email Address', 'size' => '30'))
        ->email;
        $email->addValidator('EmailAddress')
		->addFilters(array('StripTags','StringTrim','StringToLower'))
		->setRequired(true)
		->addErrorMessage('Please enter a valid address!');

		$password = $this->addElement('password', 'password',array('label' => 'Change password: ',
		'size' => '30'))
		->password;
        $password->setRequired(false);

        $institution = $this->addElement('select', 'institution',array('label' => 'Recording institution: '))->institution;
        $institution->addMultiOptions(array(
            NULL => 'Choose institution',
            'Available institutions'=> $inst_options
            ))->setAttrib('class', 'span4 selectpicker show-menu-arrow');

		$canRecord = $this->addElement('checkbox', 'canRecord',array('label' => 'Allowed to record: '))->canRecord;

        $role = $this->addElement('select', 'role',array('label' => 'Site role: '))->role;
        $role->addMultiOptions(array(NULL => 'Choose a role','Choose role' => $role_options))->setAttrib('class', 'span3 selectpicker show-menu-arrow');

        $person = $this->addElement('text', 'person',array('label' => 'Personal details attached: '))->person;

        $peopleID = $this->addElement('hidden', 'peopleID',array())->peopleID;



        $submit = new Zend_Form_Element_Submit('submit');
        $this->addElement($submit);


		$this->addDisplayGroup(array(
            'username','first_name','last_name',
            'fullname', 'preferred_name', 'email','institution',
            'role','password','person','peopleID', 'canRecord'), 'userdetails');

	$this->addDisplayGroup(array('submit'),'buttons');

	$this->setLegend('Edit account details: ');
    parent::init();
	}
}