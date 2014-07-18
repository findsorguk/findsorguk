<?php
/** Form for setting up and editing personal profile
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $form = new ProfileForm();
 * $form->removeElement('username');
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/users/controllers/AccountController.php
*/
class ProfileForm extends Pas_Form {
    
    /** The action url
     * @access protected
     * @var string
     */
    protected $_actionUrl;

    /** The copyright statement
     * @access protected
     * @var string
     */
    protected $_copyright = null;

    /** The constructor
     * @access public
     * @param string $actionUrl
     * @param array $options
     */
    public function __construct($actionUrl = null, $options=null) {
        parent::__construct($options);
        $this->setActionUrl($actionUrl);
        $this->init();
    }

    /** Set the action url
     * @access public
     * @param string $actionUrl
     * @return \ProfileForm
     */
    public function setActionUrl($actionUrl) {
        $this->_actionUrl = $actionUrl;
        return $this;
    }
    
    /** The intialisation function
     * @access public
     * @return void
     */
    public function init() {
        
        $required = true;
        
        $copyrights = new Copyrights();
        $copy = $copyrights->getTypes();
        $this->setAction($this->_actionUrl)
                ->setMethod('post')
                ->setAttrib('id', 'accountform');

        $username = $this->addElement('text','username',
                array('label' => 'Username:'))->username;
        $username = $this->getElement('username');
        $username->disabled = true;
        $username->addFilters(array('StringTrim','StripTags'));

        $firstName = $this->addElement('text', 'first_name',
                array('label' => 'First Name: ', 'size' => '30'))->first_name;
        $firstName = $this->getElement('first_name');
        $firstName->setRequired(true)
                ->addFilters(array('StringTrim','StripTags'))
                ->addErrorMessage('You must enter a firstname');

        $lastName = $this->addElement('text', 'last_name',
                array('label' => 'Last Name: ', 'size' => '30'))->last_name;
        $lastName = $this->getElement('last_name');
        $lastName->setRequired(true)
                ->addFilters(array('StringTrim','StripTags'))
                ->addErrorMessage('You must enter a surname');

        $fullname = $this->addElement('text', 'fullname',
                array('label' => 'Preferred Name: ', 'size' => '30'))->fullname;
        $fullname = $this->getElement('fullname');
        $fullname->setRequired(true)
                ->addFilters(array('StringTrim','StripTags'))
                ->addErrorMessage('You must enter your preferred name');

        $email = $this->addElement('text', 'email',
                array('label' => 'Email Address', 'size' => '30'))
                ->email;
        $email = $this->getElement('email');
        $email->setRequired(true)
                ->addErrorMessage('Please enter a valid address!')
                ->addFilters(array('StringTrim','StripTags','StringToLower'))
                ->addValidator('EmailAddress',false,array('mx' => true));

	$password = $this->addElement('password', 'password',
                array('label' => 'Change password: ', 'size' => '30'))->password;
        $password = $this->getElement('password');
        $password->addFilters(array('StringTrim','StripTags'))
                ->setRequired(false);

        $copyright = $this->addElement('select','copyright',
                array('label' => 'Default copyright: '))
                ->copyright;
        $copyright = $this->getElement('copyright');
        $copyright->setRequired(true);
        $copyright->addMultiOptions(array(
            null => 'Select a licence holder',
            'Valid copyrights' => $copy))
                ->addValidator('InArray', false, array(array_keys($copy)));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Save details');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	$this->addElements(array($hash,$submit));
	$this->addDisplayGroup(array(
            'username','first_name','last_name',
            'fullname','email','password',
            'copyright'), 
                'userdetails');

	$this->setLegend('Edit your account and profile details: ');

	$this->addDisplayGroup(array('submit'),'buttons');
	parent::init();
    }
}