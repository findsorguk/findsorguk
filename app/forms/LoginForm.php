<?php
/** Form for logging into the system
 *
 * An example of code use:
 * <code>
 * <?php
 * $form = new LoginForm();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/users/controllers/AccountController.php
*/

class LoginForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

        parent::__construct($options);

        $this->setName('login');

        $username = $this->addElement('text', 'username', array('label' => 'Username: '));
        $username = $this->getElement('username')
            ->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addValidator('Authorise')
            ->setAttrib('autofocus', 'autofocus')
            ->setAttrib('size', '20');

        $password = $this->addElement('password', 'password', array('label' => 'Password: '));
        $password = $this->getElement('password')
            ->addValidator('StringLength', true, array(3))
            ->setRequired(true)
            ->setAttrib('size', '20')
            ->setAttrib('autocomplete', 'current-password')
            ->setAttrib('id', 'current-password')
            ->addFilters(array('StringTrim', 'StripTags'));
        $password->getValidator('StringLength')
            ->setMessage('Your password is too short');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(4800);

        $this->addElement($hash);

        $submit = $this->addElement('submit', 'submit', array('label' => 'Login'));

        $this->addDisplayGroup(array('username', 'password', 'submit'), 'details');

        $this->details->setLegend('Login: ');

	parent::init();
    }
}