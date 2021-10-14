<?php
/** Form for retrieval of passwords
 * An example of use:
 * <code>
 * <?php
 * $form = new ForgotPasswordForm();
 * ?>
 * </code>
 *
 * @author        Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category      Pas
 * @package       Pas_Form
 * @version       1
 * @license       http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example       /app/modules/users/controllers/AccountController.php
 */

class ForgotPasswordForm extends Pas_Form
{

    /** Initialise form
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $username = $this->addElement(
            'Text',
            'username',
            array('label' => 'Username: ')
        );
        $username = $this->getElement('username')
            ->setRequired(true)
            ->addErrorMessage('You must enter a username')
            ->addFilters(array('StringTrim', 'StripTags', 'Purifier'));

        $email = $this->addElement(
            'Text',
            'email',
            array('label' => 'Email address: ', 'size' => '30')
        )->email;
        $email->addValidator('EmailAddress')
            ->addErrorMessage("Please enter a valid email address")
            ->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->setAttrib('placeholder', 'example@domain.co.uk');

        $this->addElement(
            (new Zend_Form_Element_Hash('csrf'))
                ->setValue($this->_salt)->setTimeout(4800)
        );

        $this->addElement(
            (new Pas_Form_Element_Recaptcha('captcha'))
                ->setLabel('Please complete the Captcha field to prove you exist')
        );

        $submit = $this->addElement('submit', 'submit');
        $submit = $this->getElement('submit')->setLabel('Retrieve my password');

        $this->addDisplayGroup(array('username', 'email', 'captcha', 'submit'), 'details');

        //  $this->setLegend('Reset my password: ');
        parent::init();
    }
}