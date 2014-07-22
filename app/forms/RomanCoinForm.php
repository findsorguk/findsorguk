<?php
/** Form for entering and editing Roman coin data
 *
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new RomanCoinForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Denominations
 * @uses Statuses
 * @uses DieAxes
 * @uses WearTypes
 * @uses Mints
 * @uses Moneyers
 * @uses Reeces
 * @uses RevTypes
 * @example /library/Pas/Controller/Action/Helper/CoinFormLoader.php
 */
class RomanCoinForm extends Pas_Form {
    
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {
	// Construct the select menu data
	$denominations = new Denominations();
	$denomination_options = $denominations->getOptionsRoman();

	$statuses = new Statuses();
	$status_options = $statuses->getCoinStatus();

	$dies = new DieAxes();
	$die_options = $dies->getAxes();

	$wears = new WearTypes;
	$wear_options = $wears->getWears();

	$rulers = new Rulers();
	$ro = $rulers->getRomanRulers();

	$mints = new Mints();
	$mo = $mints->getRomanMints();

	$reeces = new Reeces();
	$reece = $reeces->getOptions();

	$money = new Moneyers();
	$moneyers = $money->getRepublicMoneyers();

	$reverse = new RevTypes();
	$reverses = $reverse->getRevTypes();

	parent::__construct($options);

	$this->setName('romancoin');

	$denomination = new Zend_Form_Element_Select('denomination');
	$denomination->setLabel('Denomination: ')
		->setRequired(true)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose denomination', 
                    'Valid denominations' => $denomination_options))
		->addValidator('InArray', false, array(array_keys($denomination_options)))
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a denomination');

	$denomination_qualifier = new Zend_Form_Element_Radio('denomination_qualifier');
	$denomination_qualifier->setLabel('Denomination qualifier: ')
		->addMultiOptions(array(
                    '1' => 'Certain',
                    '2' => 'Probably',
                    '3' => 'Possibly'
                    ))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));;


	$ruler= new Zend_Form_Element_Select('ruler_id');
	$ruler->setLabel('Ruler: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose a ruler', 
                    'Valid rulers' => $ro
                ))
		->addValidator('InArray', false, array(array_keys($ro)));

	$ruler_qualifier = new Zend_Form_Element_Radio('ruler_qualifier');
	$ruler_qualifier->setLabel('Ruler qualifier: ')
		->addMultiOptions(array(
                    '1' => 'Certain',
                    '2' => 'Probably',
                    '3' => 'Possibly'
                    ))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$mint_id= new Zend_Form_Element_Select('mint_id');
	$mint_id->setLabel('Issuing mint: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose a mint', 
                    'Valid mints' => $mo
                ))
		->addValidator('InArray', false, array(array_keys($mo)))
		->addFilters(array('StripTags', 'StringTrim'));

	$mint_qualifier = new Zend_Form_Element_Radio('mint_qualifier');
	$mint_qualifier->setLabel('Mint qualifier: ')
		->addMultiOptions(array(
                    '1' => 'Certain',
                    '2' => 'Probably',
                    '3' => 'Possibly'
                    ))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$reeceID = new Zend_Form_Element_Select('reeceID');
	$reeceID->setLabel('Reece period: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose a Reece period',
                    'Valid periods' => $reece
                ))
		->addValidator('InArray', false, array(array_keys($reece)))
		->addFilters(array('StripTags', 'StringTrim'));

	$moneyer = new Zend_Form_Element_Select('moneyer');
	$moneyer->setLabel('Republican Moneyer: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose a moneyer', 
                    'Valid moneyers' => $moneyers
                ))
		->addValidator('InArray', false, array(array_keys($moneyers)))
		->addFilters(array('StripTags', 'StringTrim'));

	$moneyer_qualifier = new Zend_Form_Element_Radio('moneyer_qualifier');
	$moneyer_qualifier->setLabel('Republican Moneyer qualifier: ')
		->addMultiOptions(array(
                    '1' => 'Certain',
                    '2' => 'Probably',
                    '3' => 'Possibly'
                    ))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$revtypeID = new Zend_Form_Element_Select('revtypeID');
	$revtypeID->setLabel('Reverse type: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose a reverse type', 
                    'Valid reverses' => $reverses
                ))
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('InArray', false, array(array_keys($reverses)));

	$revTypeID_qualifier = new Zend_Form_Element_Radio('revTypeID_qualifier');
	$revTypeID_qualifier->setLabel('Reverse type qualifier: ')
		->addMultiOptions(array(
                    '1' => 'Certain',
                    '2' => 'Probably',
                    '3' => 'Possibly'
                    ))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$status = new Zend_Form_Element_Select('status');
	$status->setLabel('Status: ')
		->setValue(1)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose coin status', 
                    'Valid options' => $statuses
                ))
		->addValidator('InArray', false, array(array_keys($statuses)))		;

	$status_qualifier = new Zend_Form_Element_Radio('status_qualifier');
	$status_qualifier->setLabel('Status qualifier: ')
		->addMultiOptions(array(
                    '1' => 'Certain',
                    '2' => 'Probably',
                    '3' => 'Possibly'
                    ))
		->setValue(1)
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));

	$degree_of_wear = new Zend_Form_Element_Select('degree_of_wear');
	$degree_of_wear->setLabel('Degree of wear: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose coin wear status', 
                    'Valid options' => $wear_options
                ))
		->addValidator('InArray', false, array(array_keys($wear_options)))
		->addFilters(array('StripTags', 'StringTrim'));

	$obverse_inscription = new Zend_Form_Element_Text('obverse_inscription');
	$obverse_inscription->setLabel('Obverse inscription: ')
		->setAttrib('class','span6')
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_inscription = new Zend_Form_Element_Text('reverse_inscription');
	$reverse_inscription->setLabel('Reverse inscription: ')
		->setAttrib('class','span6')
		->addFilters(array('StripTags', 'StringTrim'));

	$obverse_description = new Zend_Form_Element_Textarea('obverse_description');
	$obverse_description->setLabel('Obverse description: ')
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_description = new Zend_Form_Element_Textarea('reverse_description');
	$reverse_description->setLabel('Reverse description: ')
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));

	$reverse_mintmark = new Zend_Form_Element_Textarea('reverse_mintmark');
	$reverse_mintmark->setLabel('Reverse mintmark: ')
		->setAttribs(array('rows' => 3, 'cols' => 80, 'class' => 'span6'))
		->addFilters(array('StripTags', 'StringTrim'));


	$die_axis_measurement = new Zend_Form_Element_Select('die_axis_measurement');
	$die_axis_measurement->setLabel('Die axis measurement: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose die axis', 
                    'Available axes' => $die_options
                ))
		->addValidator('InArray', false, array(array_keys($die_options)))
		->addFilters(array('StripTags', 'StringTrim'));

	$die_axis_certainty = new Zend_Form_Element_Radio('die_axis_certainty');
	$die_axis_certainty->setLabel('Die axis certainty: ')
		->addMultiOptions(array(
                    '1' => 'Certain',
                    '2' => 'Probably',
                    '3' => 'Possibly'
                    ))
		->addFilters(array('StripTags', 'StringTrim'))
		->setOptions(array('separator' => ''));


	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
            $ruler, $denomination, $moneyer,
            $mint_id, $reeceID, $status,
            $revtypeID, $degree_of_wear, $obverse_description,
            $obverse_inscription, $reverse_description, $reverse_inscription,
            $die_axis_measurement, $die_axis_certainty, $mint_qualifier,
            $ruler_qualifier, $denomination_qualifier, $status_qualifier,
            $revTypeID_qualifier, $reverse_mintmark,
            $submit));

	$this->addDisplayGroup(array(
            'denomination','denomination_qualifier','ruler_id',
            'ruler_qualifier','mint_id','mint_qualifier',
            'reeceID','revtypeID','revTypeID_qualifier',
            'moneyer','status',
            'status_qualifier','degree_of_wear','obverse_description',
            'obverse_inscription','reverse_description','reverse_inscription',
            'reverse_mintmark','die_axis_measurement','die_axis_certainty'
            ), 
                'details');
	$this->addDisplayGroup(array('submit'),'buttons');
	
        parent::init();
    }
}