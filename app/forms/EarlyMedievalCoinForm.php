<?php
/** Form for entering data about Early Medieval coins
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new EarlyMedievalCoinForm();
 * $form->details->setLegend('Add Early Medieval numismatic data');
 * $form->submit->setLabel('Add Early Medieval data');
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /library/Pas/Controller/Action/Helper/CoinFormLoader.php
 * @uses CategoriesCoins 
 * @uses Denominations
 * @uses Statuses
 * @uses DieAxes
 * @uses WearTypes
 * @uses Rulers
 * @uses Mints
 */
class EarlyMedievalCoinForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	$cats = new CategoriesCoins();
	$cat_options = $cats->getPeriodEarlyMed();

	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsEarlyMedieval();

	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();

	$dies = new DieAxes();
	$die_options = $dies->getAxes();

	$wears = new WearTypes();
	$wear_options = $wears->getWears();

	$rulers = new Rulers();
	$ro = $rulers->getEarlyMedRulers();

	$mints = new Mints();
	$mo = $mints->getEarlyMedievalMints();

	parent::__construct($options);

	$this->setName('earlymedievalcoin');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
        	->addValidators(array('NotEmpty'))
                ->addMultiOptions(array(null => 'Choose denomination', 'Available denominations' => $denomination_options))
                ->addValidator('InArray', false, array(array_keys($denomination_options)))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
                ->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
                ->addFilters(array('StripTags','StringTrim'))
                ->setOptions(array('separator' => ''))
                ->addValidator('Int');

	$categoryID = new Zend_Form_Element_Select('categoryID');
	$categoryID->setLabel('Category of coin: ')
                ->addValidators(array('NotEmpty'))
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(null => 'Choose category', 'Available categories' => $cat_options))
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int');

	$ruler_id= new Zend_Form_Element_Select('ruler_id');
	$ruler_id->setLabel('Ruler: ')
                ->addValidator('InArray', false, array(array_keys($ro)))
                ->addMultiOptions(array(null => 'Choose a ruler','Available rulers' => $ro))
                ->addValidator('Int')
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addFilters(array('StripTags','StringTrim'));

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Issuer qualifier: ')
                ->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
                ->setOptions(array('separator' => ''));

	$mint_id = new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
                ->addValidator('InArray', false, array(array_keys($mo)))
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(null => 'Please choose a mint','Available mints' => $mo));

	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
                ->setRegisterInArrayValidator(false)
                ->setValue(1)
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
                ->addMultiOptions(array(null => 'Choose coin status','Available status' => $status_options));

	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
                ->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
                ->setValue(1)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
                ->setOptions(array('separator' => ''));

	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
                ->setRegisterInArrayValidator(false)
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(null => 'Choose a degree of wear','Available options' => $wear_options))
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int');

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
                ->setAttrib('class','span6')
                ->addFilters(array('StripTags','StringTrim'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
                ->setAttrib('class','span6')
                ->addFilters(array('StripTags','StringTrim'));

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
                ->setAttribs(array('rows' => 5, 'cols' => 40, 'class' => 'span6'))
                ->addFilters(array('StripTags','EmptyParagraph','StringTrim'))
                ->addFilters(array('StripTags','StringTrim'));

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
                ->setAttribs(array('rows' => 5, 'cols' => 40, 'class' => 'span6'))
                ->addFilters(array('StripTags','EmptyParagraph','StringTrim'));

	$rev_mm = new Zend_Form_Element_Textarea('reverse_mintmark');
	$rev_mm->setLabel('Reverse mintmark: ')
                ->setAttribs(array('rows' => 5, 'cols' => 40, 'class' => 'span6'))
                ->addFilters(array('StripTags','EmptyParagraph','StringTrim'));

	$initial = new Zend_Form_Element_Textarea('initial_mark');
	$initial->setLabel('Initial mark: ')
                ->addValidators(array('NotEmpty'))
                ->setAttribs(array('rows' => 5, 'cols' => 40, 'class' => 'span6'))
                ->addFilters(array('StripTags','EmptyParagraph','StringTrim'));

	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
                ->setRegisterInArrayValidator(false)
                ->addFilters(array('StripTags','StringTrim'))
                ->addMultiOptions(array(null => 'Choose die axis','Available options' => $die_options));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
                ->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
                ->addFilters(array('StripTags','StringTrim'))
                ->setOptions(array('separator' => ''))
                ->addValidator('Int');

	$typeID = new Zend_Form_Element_Select('typeID');
	$typeID->setLabel('Coin type: ')
                ->setRegisterInArrayValidator(false)
                ->addValidator('Int')
                ->setAttribs(array('class' => 'span6'))
                ->addFilters(array('StripTags','StringTrim'));

	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElements(array(
            $ruler_id, $ruler_qualifier, $denomination,
            $denomination_qualifier, $mint_id, $typeID,
            $status, $categoryID, $status_qualifier,
            $degree_of_wear, $obverse_description, $obverse_inscription,
            $reverse_description, $reverse_inscription, $die_axis_measurement,
            $die_axis_certainty, $submit, $rev_mm,
            $initial));

	$this->addDisplayGroup(array(
            'categoryID', 'ruler_id','typeID',
            'ruler_qualifier', 'denomination', 'denomination_qualifier',
            'mint_id', 'status', 'status_qualifier',
            'degree_of_wear', 'obverse_description', 'obverse_inscription',
            'reverse_description', 'reverse_inscription', 'reverse_mintmark',
            'initial_mark', 'die_axis_measurement', 'die_axis_certainty') ,
                'details');

	$this->addDisplayGroup(array('submit'),'buttons');
	parent::init();
    }
}