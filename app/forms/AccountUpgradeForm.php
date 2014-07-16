<?php
/** Form for requesting an upgrade for a user's account
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://URL name
 */
class AccountUpgradeForm extends Pas_Form {

    /** The form constructor
     * @access public
     * @param array $options
     */
    public function __construct(array $options) {

    parent::__construct($options);

    $this->setName('accountupgrades');

    $researchOutline = new Pas_Form_Element_CKEditor('researchOutline');
    $researchOutline->setLabel('Research outline: ')
            ->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400))
            ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
            ->addErrorMessage('Outline must be present.')
            ->setDescription('Use this textarea to tell us whether you want to 
                become a research level user and why. We would also like to know 
                the probable length of time for this project so that we can 
                inform our research board of progress. We need a good idea,
                as we have to respect privacy of findspots and 
                landowner/finder personal data');
            
    $reference = $this->addElement('Text','reference',
            array(
                'label' => 'Please provide a referee:', 'size' => '40',
                'description' => 'We ask you to provide a referee who can 
                    substantiate your request for higher level access.
                    Ideally they will be an archaeologist of good standing.'))
                    ->reference;
    $reference->setRequired(false)->addFilters(array('StripTags', 'StringTrim'));

    $referenceEmail = $this->addElement('Text','referenceEmail',
            array(
                'label' => 'Please provide an email address for your referee:', 
                'size' => '40'))->referenceEmail;
    $referenceEmail->setRequired(false)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator('EmailAddress');

    $already = new Zend_Form_Element_Radio('already');
    $already->setLabel('Is your topic already listed on our research register?: ')
            ->addMultiOptions(array( 1 => 'Yes it is',0 => 'No it isn\'t' ))
            ->setRequired(true)
            ->setOptions(array('separator' => ''));


    //Submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Submit request');

    $this->addElements(array($researchOutline, $submit, $already,));

    $this->addDisplayGroup(array(
        'researchOutline', 'reference', 'referenceEmail',
        'already'), 'details');

    $this->details->setLegend('Details: ');
    $this->addDisplayGroup(array('submit'), 'buttons');
    parent::init();
    }
}