<?php
/** Form for activating an account
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class ActivateForm extends Pas_Form {

    /** Construct the form
     * @access public
     * @param array $options
     */
    public function __construct(array $options = null) {
        parent::__construct($options);
        $this->init();
    }

    /** Initialise the form
     * @access public
     * @return void
     */
    public function init() {

    	$username = new Zend_Form_Element_Text('username');
    	$username->setLabel('Username: ');
    	$username->setRequired(true)
                ->addFilters(array('StringTrim', 'StripTags'))
    		->addValidator('Db_RecordExists', false, 
                        array(
                            'table' => 'users',
                            'field' => 'username'));
    	
    	$activationKey = new Zend_Form_Element_Text('activationKey');
        $activationKey->setLabel('Activation key: ');
        $activationKey->setDescription('Your key was sent in your activation email')
                ->setRequired(true)
                ->addFilters(array('StringTrim', 'StripTags'))
		->addValidator('Db_RecordExists', false, 
                        array(
                            'table' => 'users',
                            'field' => 'activationKey'));
		
        $email = new Zend_Form_Element_Text('email');
        $email->setLabel('Email address: ');
        $email->setRequired(true)
                ->setAttrib('placeholder','example@domain.co.uk')
                ->addValidator('Db_RecordExists', false, 
                        array(
                            'table' => 'users',
                            'field' => 'email'))
    		->addValidator('EmailAddress',false, array('mx' => true));

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(4800);

        $captcha = new Pas_Form_Element_Recaptcha('captcha');
        $captcha->setLabel('Please complete the Captcha field to prove you exist');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Activate me!');

        $this->addElement($submit);
        $this->addElements(array( $username, $activationKey, $email,  $hash, $captcha));
        $this->addDisplayGroup(array('username','email','activationKey', 'captcha'), 'userdetails');
        $this->addDisplayGroup(array('submit'), 'buttons');
        $this->setLegend('Enter details: ');
    	parent::init();
	}
}