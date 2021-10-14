<?php

/** Form for changing a user's password
 * An example of use:
 * <code>
 * <?php
 * $form = new ChangePasswordForm();
 * ?>
 * </code>
 *
 * @author        Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category      Pas
 * @package       Pas_Form
 * @copyright     Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license       http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version       1
 * @example       /app/modules/users/controllers/AccountController.php
 */
class ChangePasswordForm extends Pas_Form
{

    /** The constructor
     *
     * @access public
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        parent::__construct($options);
        $this->init();
    }

    /** Initialise the form
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $oldpassword = new Zend_Form_Element_Password('oldpassword');
        $oldpassword->setLabel('Your old password: ');
        $oldpassword->setRequired(true)
            ->addValidator('RightPassword')
            ->setAttrib('autocomplete', 'current-password')
            ->setAttrib('id', 'current-password')
            ->addFilters(array('StripTags', 'StringTrim'));

        $password = $this->addElement(
            'password',
            'password',
            array('label' => 'New password:')
        );
        $password = $this->getElement('password')
            ->setRequired(true)
            ->setDescription(
                'Passwords should be at least 8 characters, contain letters and numbers and not use "<" or ">"'
            )
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addValidator('StringLength', true, array(8))
            ->addValidator('Regex', true, array('/^(?=.*\d)(?=.*[a-zA-Z])(?!.*<)(?!.*>).{8,}$/'))
            ->setAttrib('pattern', '^(?=.*\d)(?=.*[a-zA-Z])(?!.*<)(?!.*>).{8,}$') //HTML 5 front end validation
            ->setRequired(true)
            ->setAttrib('autocomplete', 'new-password')
            ->setAttrib('id', 'new-password')
            ->addDecorators(array(array('HtmlTag', array('tag' => 'div', 'openOnly' => true))));
        $password->getValidator('StringLength')->setMessage('Password is too short');
        $password->getValidator('Regex')->setMessage(
            'Password does not contain letters and numbers, or contains "<" or ">"'
        );

        // identical field validator with custom messages
        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(60);

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Confirm password:')
            ->addValidator('NotEmpty')
            ->setAttrib('autocomplete', 'new-password')
            ->setAttrib('id', 'new-password')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setRequired(true);

        $this->addElement(
            (new Pas_Form_Element_Recaptcha('captcha'))
                ->setLabel('Please complete the Captcha field to prove you exist')
        );

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElement($submit);
        $this->addElements(array($oldpassword, $password, $password2, $submit, $hash));

        $this->addDisplayGroup(array('oldpassword', 'password', 'password2', 'captcha'), 'userdetails');

        $this->addDisplayGroup(array('submit'), 'buttons');

        $this->setLegend('Edit account details: ');

        parent::init();
    }
}