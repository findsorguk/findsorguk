<?php
/** Form for creating an account for a user
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/

class AccountForm extends Pas_Form {

    /** The constructor
     * @access @access public
     * @param array $options
     */
    public function __construct(array $options = null) {
        parent::__construct($options);
        $this->init();
    }



    /** Initalise the form
     * 
     */
    public function init() {

        $username = $this->addElement('text','username',
                array('label' => 'Username: '))->username;
        $username->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('StringLength', true, array('max' => 40))
                ->setRequired(true);
        $username->getValidator('StringLength')
                ->setMessage('Username is too long');

        $password = $this->addElement('password', 'password',
                                      array('label' => 'New password: '));
        $password = $this->getElement('password')
            ->setRequired(true)
            ->setDescription('Passwords should be at least 8 characters, contain letters and numbers and not use "<" or ">"')
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addValidator('StringLength', true, array(8))
            ->addValidator('Regex', true, array('/^(?=.*\d)(?=.*[a-zA-Z])(?!.*<)(?!.*>).{8,}$/'))
            ->setAttrib('pattern', '^(?=.*\d)(?=.*[a-zA-Z])(?!.*<)(?!.*>).{8,}$') //HTML 5 front end validation
            ->setRequired(true)
            ->setAttrib('autocomplete','new-password')
            ->setAttrib('id','new-password')
            ->addDecorators(array(array('HtmlTag',array('tag' => 'div', 'openOnly' => true ))));
        $password->getValidator('StringLength')->setMessage('Password is too short');
        $password->getValidator('Regex')->setMessage('Password does not contain letters and numbers, or contains "<" or ">"');

        $firstName = $this->addElement('text', 'first_name',
                array('label' => 'First name: ', 'size' => '30'))->first_name;
        $firstName->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addErrorMessage('You must enter a firstname');

        $lastName = $this->addElement('text', 'last_name',
                array('label' => 'Last name: ', 'size' => '30'))->last_name;
        $lastName->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                 ->addErrorMessage('You must enter a surname');

       $fullname = $this->addElement('text', 'fullname',
                array('label' => 'Preferred name: ', 'size' => '30'))->fullname;
       $fullname->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addErrorMessage('You must enter your preferred name');

        $email = $this->addElement('Text', 'email',
                                   array('label' => 'Email address: ', 'size' => '30'))->email;
        $email->addValidator('EmailAddress')
            ->addErrorMessage("Please enter a valid email address")
            ->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->setAttrib('placeholder','example@domain.co.uk');

       $institution = $this->addElement('text', 'institution',
               array('label' => 'Recording institution: ', 'size' => '30'))->institution;


       $researchOutline = $this->addElement('textArea','research_outline',
                                    array(
                                        'label' => 'Outline your research: ',
                                        'rows' => 10, 'cols' => 40)
               )->research_outline;

       $researchOutline->setRequired(false)
                ->addFilter('HtmlBody')
                ->addFilter('EmptyParagraph');

        $reference = $this->addElement('text','reference',
                array(
                    'label' => 'Please provide a referee: ',
                    'size' => '40')
                )->reference;

        $reference->setRequired(false)
                ->addFilters(array('StripTags', 'StringTrim'));

        $referenceEmail = $this->addElement('text','reference_email',
                    array(
                        'label' => 'Please provide an email address for your referee: ',
                        'size' => '40')
                )->reference_email;
        $referenceEmail->setRequired(false)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('EmailAddress');

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Set my account up on PAS');

        $this->addElements(array($submit));

        $this->addDisplayGroup(array(
            'username', 'password', 'first_name',
            'last_name', 'fullname', 'email',
            'institution', 'research_outline', 'reference',
            'reference_email'),
            'userdetails');

        $this->addDisplayGroup(array('submit'), 'buttons');

        $this->setLegend('Edit account details: ');
        parent::init();
    }
}