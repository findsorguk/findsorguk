<?php

/** Form for editing a user's account details
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new EditAccountForm();
 * $form->submit->setLabel('Edit account details');
 * $form->removeElement('password');
 * $this->view->form = $form;
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/UsersController.php
 * @uses Roles
 * @uses Institutions
 */
class EditAccountForm extends Pas_Form
{

    /** The action url
     *
     *
     * /** Constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->init();
    }

    /** Initialise the form
     * @access public
     * @return void
     */
    public function init()
    {

        $roles = new Roles();
        $role_options = $roles->getRoles();

        $inst = new Institutions();
        $inst_options = $inst->getInsts();

        $username = $this->addElement('text', 'username',
            array('label' => 'Username: '))->username;

        $username->addFilters(array('StripTags', 'StringTrim'))
            ->setRequired(true);

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

        $email = $this->addElement('text', 'email',
            array('label' => 'Email Address', 'size' => '30'))
            ->email;
        $email->addValidator('EmailAddress')
            ->addFilters(array('StripTags', 'StringTrim', 'StringToLower'))
            ->setRequired(true)
            ->addErrorMessage('Please enter a valid address!');

        $password = $this->addElement('password', 'password', array('label' => 'Change password: ', 'size' => '30'))
            ->password;
        $password->setRequired(false);

        $institution = $this->addElement('select', 'institution',
            array('label' => 'Recording institution: '))->institution;
        $institution->addMultiOptions(array(
            null => 'Choose institution',
            'Available institutions' => $inst_options
        ))->setAttrib('class', 'input-xlarge selectpicker show-menu-arrow');

        $canRecord = $this->addElement('checkbox', 'canRecord',
            array('label' => 'Allowed to record: '))->canRecord;

        $role = $this->addElement('select', 'role',
            array('label' => 'Site role: '))->role;
        $role->addMultiOptions(array(
            null => 'Choose a role',
            'Available roles' => $role_options))
            ->setAttrib('class', 'input-medium selectpicker show-menu-arrow');

        $person = $this->addElement('text', 'person',
            array('label' => 'Personal details attached: '))->person;
        $peopleID = $this->addElement('hidden', 'peopleID', array())->peopleID;

        $valid = $this->addElement('checkbox', 'valid',
            array('label' => 'Valid record: '))->valid;

	$activationKey = $this->addElement('text', 'activationKey',
            array('label' => 'Activation Key: '))->activationKey;

        $submit = new Zend_Form_Element_Submit('submit');
        $this->addElement($submit);

        $this->addDisplayGroup(array(
            'username', 'first_name', 'last_name',
            'fullname', 'preferred_name', 'email',
            'institution', 'role', 'password',
            'person', 'peopleID', 'activationKey', 
	    'canRecord', 'valid'),
            'userdetails');

        $this->addDisplayGroup(array('submit'), 'buttons');

        $this->setLegend('Edit account details: ');
        parent::init();
    }
}
