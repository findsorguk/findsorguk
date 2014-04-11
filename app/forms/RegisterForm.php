<?php
/** Form for registering with the website.
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RegisterForm extends Pas_Form {
protected $_actionUrl;

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
    $this->setAction($this->_actionUrl)
         ->setMethod('post')
         ->setAttrib('id', 'registerform');



    $username = $this->addElement('Text', 'username', array('label' => 'Username'))->username;
    $username = $this->getElement('username')
            ->addValidator('UsernameUnique', true,
            array('id','username','id','Users'))
            ->addValidator('StringLength', true, array(4))
            ->addValidator('Alnum', false, array('allowWhiteSpace' => false))
            ->setRequired(true)
            ->addFilters(array('StringToLower','StringTrim', 'StripTags'))
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

    $firstName = $this->addElement('Text', 'first_name', array('label' => 'First Name', 'size' => '30'))->first_name;
    $firstName = $this->getElement('first_name');
    $firstName->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addErrorMessage('You must enter a firstname');

    $lastName = $this->addElement('Text', 'last_name', array('label' => 'Last Name', 'size' => '30'))->last_name;
    $lastName = $this->getElement('last_name');
    $lastName->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addErrorMessage('You must enter a surname');

    $preferredName = $this->addElement('Text', 'preferred_name', array('label' => 'Preferred Name', 'size' => '30'))->preferred_name;
    $preferredName = $this->getElement('preferred_name');
    $preferredName->setDescription('e.g. Joe Brown rather than Joseph Brown')
            ->setRequired(true)
            ->addFilters(array('StringToLower','StringTrim', 'StripTags'))
            ->addErrorMessage('You must enter your preferred name');

    $email = $this->addElement('Text', 'email', array('label' => 'Email Address', 'size' => '30'))->email;
    $email = $this->getElement('email');
    $email->addValidator('EmailAddress',false, array('mx' => true))
            ->setRequired(true)
            ->addFilters(array('StringToLower','StringTrim', 'StripTags'))
            ->addValidator('Db_NoRecordExists', false, array('table' => 'users',
                                                           'field' => 'email'));

    $hash = new Zend_Form_Element_Hash('csrf');
    $hash->setValue($this->_salt)
            ->setTimeout(4800);
    $this->addElement($hash);

  	$recaptcha = new Zend_Service_ReCaptcha($this->_pubKey, $this->_privateKey);
	$captcha = new Zend_Form_Element_Captcha('captcha', array(
                        		'captcha' => 'ReCaptcha',
								'label' => 'Prove you are not a robot/spammer',
                                'captchaOptions' => array(
                                'captcha' => 'ReCaptcha',
                                'service' => $recaptcha,
								'theme'=> 'clean')
                        ));
    $captcha->setDescription('Due to the surge in robotic activity, we have
        had to introduce this software. However, by filling in this captcha, you help Carnegie Mellon University digitise old books.');
    $captcha->setDecorators(array(array('Description', array('placement' => 'append','class' => 'info')),            array('Errors',array('placement' => 'append','class'=>'error','tag' => 'li'))));
    $captcha->addErrorMessage('You have not solved the captcha');
    $this->addElement($captcha);

           //Submit button
    $submit = new Zend_Form_Element_Submit('submit');
   	$submit->setLabel('Register!');

    $this->addElement($submit);

    $this->addDisplayGroup(array('username','password','first_name','last_name','preferred_name','email','captcha'), 'details');
    $this->details->setLegend('Register with the Scheme: ');


    $this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();

    }
}