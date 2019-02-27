<?php

/** Form for registering with the website.
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new RegisterForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/users/controllers/AccountController.php
 * @version 1
 */
class RegisterForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->init();
    }


    /** Init the form
     * @access public
     * @return void
     */
    public function init()
    {
        $username = $this->addElement('Text', 'username', array('label' => 'Username'))->username;
        $username = $this->getElement('username')
            ->addValidator('UsernameUnique', true,
                array('id', 'username', 'id', 'Users'))
            ->addValidator('StringLength', true, array(4))
            ->addValidator('Alnum', false, array('allowWhiteSpace' => false))
            ->setRequired(true)
            ->addFilters(array('StringToLower', 'StringTrim', 'StripTags'))
            ->addValidator('Db_NoRecordExists', false, array('table' => 'users',
                'field' => 'username'))
            ->setDescription('Username must be more than 3 characters and include only letters and numbers');
        $username->getValidator('Alnum')
            ->setMessage('Your username must be letters and digits only');

        $password = $this->addElement('Password', 'password', array('label' => 'Password'))->password;
        $password = $this->getElement('password');
        $password->setDescription('Password must be longer than 6 characters and must include
        letters and numbers i.e. p4ssw0rd')
            ->addValidator('StringLength', true, array(6))
            ->addValidator('Regex', true, array('/^(?=.*\d)(?=.*[a-zA-Z]).{6,}$/'))
            ->setRequired(true)
            ->addErrorMessage('Please enter a valid password!');
        $password->getValidator('StringLength')->setMessage('Password is too short');
        $password->getValidator('Regex')->setMessage('Password does not contain letters and numbers');

        $firstName = $this->addElement('Text', 'first_name',
            array('label' => 'First Name', 'size' => '30'))->first_name;
        $firstName = $this->getElement('first_name');
        $firstName->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addErrorMessage('You must enter a firstname');

        $lastName = $this->addElement('Text', 'last_name',
            array('label' => 'Last Name', 'size' => '30'))->last_name;
        $lastName = $this->getElement('last_name');
        $lastName->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addErrorMessage('You must enter a surname');

        $preferredName = $this->addElement('Text', 'preferred_name',
            array('label' => 'Preferred Name', 'size' => '30'))->preferred_name;
        $preferredName = $this->getElement('preferred_name');
        $preferredName->setDescription('e.g. Joe Brown rather than Joseph Brown')
            ->setRequired(true)
            ->addFilters(array('StringToLower', 'StringTrim', 'StripTags'))
            ->addErrorMessage('You must enter your preferred name');

        $email = $this->addElement('Text', 'email',
            array('label' => 'Email Address', 'size' => '30'))->email;
        $email = $this->getElement('email');
        $email->addValidator('EmailAddress', false, array('mx' => true))
            ->setRequired(true)
            ->addFilters(array('StringToLower', 'StringTrim', 'StripTags'))
            ->addValidator('Db_NoRecordExists', false, array('table' => 'users',
                'field' => 'email'));

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)
            ->setTimeout(4800);
        $this->addElement($hash);

        $captcha = new Pas_Form_Element_Recaptcha('captcha');
        $captcha->setLabel('You have not solved the captcha');

        //Submit button
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Register!');

        //$this->addElement($submit);
        $this->addElements(array(
	   $username,
	   $password,
	   $firstName,
	   $lastName,
	   $preferredName,
	   $email,
           $captcha,
           $submit, 
           $hash));

        $this->addDisplayGroup(array(
            'username', 'password', 'first_name',
            'last_name', 'preferred_name', 'email',
            'captcha'),
            'details');

        $this->details->setLegend('Register with the Scheme: ');
        $this->addDisplayGroup(array('submit'), 'buttons');

        $this->addPrefixPath('Pas\Form\Element', APPLICATION_PATH . '/../Pas/Form/Element', Zend_Form::ELEMENT);
        $this->addElementPrefixPath('Pas\Validate', APPLICATION_PATH . '/../Pas/Validate/', Zend_Form_Element::VALIDATE);

        parent::init();

    }
}
