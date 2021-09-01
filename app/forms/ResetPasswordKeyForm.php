<?php

/** Form for retrieval of passwords
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new ResetPasswordKeyForm();
 * $this->view->form = $form;
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/user/controllers/AccountController.php
 */
class ResetPasswordKeyForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->init();
    }

    /** Init the form
     * @return void
     * @access public
     */
    public function init()
    {
        $this->setMethod('post')->setAttrib('id', 'resetpassword');

        $username = $this->addElement('Text', 'username',
            array('label' => 'Username:'));

        $username = $this->getElement('username')
            ->setRequired(true)
            ->addErrorMessage('You must enter a username')
            ->addFilters(array('StringTrim', 'StripTags', 'Purifier'));

        $activationKey = $this->addElement('Text', 'activationKey',
            array('label' => 'Reset password key:'));
        $activationKey = $this->getElement('activationKey')
            ->setDescription('The reset key can be found in the email you received when asking for a new password. 
            Please check your spam folder if you are unable to find it.')
            ->setRequired(true)
            ->addValidator('StringLength', true, array('min'=>'2', 'messages'=>'You must enter a reset key'))
            ->addFilters(array('StringTrim', 'StripTags', 'Purifier'));

        $password = $this->addElement('password', 'password',
            array('label' => 'New password:'));
        $password = $this->getElement('password')
            ->setRequired(true)
            ->setDescription('Passwords should be at least 8 characters, contain letters and numbers and not use "<" or ">"')
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addValidator('StringLength', true, array(8))
            ->addValidator('Regex', true, array('/^(?=.*\d)(?=.*[a-zA-Z])(?!.*<)(?!.*>).{8,}$/'))
            ->setAttrib('pattern', '^(?=.*\d)(?=.*[a-zA-Z])(?!.*<)(?!.*>).{8,}$') //HTML 5 front end validation
            ->setRequired(true)
            ->setAttrib('autocomplete','new-password')
            ->setAttrib('id','new-password');
        $password->getValidator('StringLength')->setMessage('Password is too short');
        $password->getValidator('Regex')->setMessage('Password does not contain letters and numbers, or contains "<" or ">"');

        $confirmpassword=$this->addElement('password','confirmpassword',
                                           array('label'=>'Confirm password:'));
        $confirmpassword=$this->getElement('confirmpassword')
            ->setRequired(true)
            ->setDescription('Please confirm your password')
            ->addFilters(array('StringTrim'))
            ->addValidator('Identical',false,array('token'=>'password', 'messages'=>'Passwords do not match'))
            ->setRequired(true)
            ->setAttrib('autocomplete','new-password');

        $email = $this->addElement('Text', 'email',
                                   array('label' => 'Email Address:', 'size' => '30'))->email;
        $email->addValidator('EmailAddress')
            ->addErrorMessage("Please enter a valid email address")
            ->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->setAttrib('placeholder','example@domain.co.uk');

        $hash = new Zend_Form_Element_Hash('csrf');

        $hash->setValue($this->_salt)
            ->setTimeout(4800);
        $this->addElement($hash);

        $captcha = new Pas_Form_Element_Recaptcha('captcha');
        $captcha->setLabel('Please complete the Captcha field to prove you exist');

        $this->addElement($captcha);
        $submit = $this->addElement('submit', 'submit');
        $submit = $this->getElement('submit')
            ->setLabel('Change my password');

        $this->addDisplayGroup(array(
            'username', 'email', 'password', 'confirmpassword',
            'activationKey', 'captcha', 'submit'
        ), 'details');

        $this->setLegend('Reset my password: ');

        parent::init();
    }
}
