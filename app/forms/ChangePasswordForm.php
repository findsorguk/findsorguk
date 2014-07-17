<?php
/** Form for changing a user's password
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new ChangePasswordForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/users/controllers/AccountController.php
*/
class ChangePasswordForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param string $actionUrl
     * @param array $options
     */
    public function __construct($actionUrl, array $options) {
        parent::__construct($options);
        $this->init();
    }

    /** Initialise the form
     * @access public
     * @return void
     */
    public function init() {
        $oldpassword = new Zend_Form_Element_Password('oldpassword');
        $oldpassword->setLabel('Your old password: ');
        $oldpassword->setRequired(true)
                ->addValidator('RightPassword')
                ->addFilters(array('StripTags','StringTrim'));

        $password = new Zend_Form_Element_Password('password');
        $password->setLabel('New password:')
                ->addValidator('NotEmpty')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('IdenticalField', false, 
                        array('password2', ' confirm password field'));

        // identical field validator with custom messages
        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(60);

        $password2 = new Zend_Form_Element_Password('password2');
        $password2->setLabel('Confirm password:')
                ->addValidator('NotEmpty')
                ->addFilters(array('StripTags','StringTrim'))
                          ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElement($submit);
        $this->addElements(array( $oldpassword, $password, $password2, $submit, $hash));

        $this->addDisplayGroup(array('oldpassword','password','password2'), 'userdetails');

        $this->addDisplayGroup(array('submit'),'buttons');

        $this->setLegend('Edit account details: ');

        parent::init();
    }
}