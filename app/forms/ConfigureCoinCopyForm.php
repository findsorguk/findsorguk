<?php
/** Form for configuring which fields to copy for the coin form when
 * add last record is activated.
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ConfigureCoinCopyForm extends Pas_Form {

	public function __construct($options = null) {
	
	//Get the coins columns from the table's schema	
	$finds = new Coins();
	$schema = $finds->info();

	//Flip the array of columns
	$fields = array_flip($schema['cols']);
	
	//Which fields to remove from this as they aren't editable by the staff
	$remove = array(
		'id', 'secuid', 'old_findID',
		'updated', 'created', 'updatedBy',
		'createdBy', 'institution', 'secwfstage',
		'secowner', 'sectag', 'old_candidate',
		'old_finderID', 'objdate2subperiod_old', 'objdate1subperiod_old',
		'finder2ID', 'datefound2flag', 'datefound1flag', 
		'findID', 'phase_date_1', 'phase_date_2',
		'context', 'depositionDate', 'volume',
		'reference', 'classification'
	);
	
	//What are the friendly labels?
	$labels = array(
		'geographyID'				=> 'Iron Age geographical region',
		'geography_qualifier'		=> 'Geographical qualifier',
		'greekstateID' 				=> 'Greek state',
		'ruler_id' 					=> 'Primary ruler',
		'ruler2_id'					=> 'Secondary ruler',
		'ruler_qualifier'			=> 'primary ruler qualifier',
		'ruler2_qualifier' 			=> 'Secondary ruler qualifier',
		'tribe_qualifier'			=> 'Ascribed culture',
		'denomination_qualifier'	=> 'Denomination qualifier',
		'mint_id'					=> 'Mint',
		'mint_qualifier'			=> 'Mint qualifier',
		'categoryID'				=> 'Medieval category',
		'typeID'					=> 'Medieval type',
		'status_qualifier'			=> 'Status qualifier',
		'obverse_description'		=> 'Obverse description',
		'obverse_inscription'		=> 'Obverse inscription',
		'initial_mark'				=> 'Initial mark',
		'reverse_description'		=> 'Reverse description',
		'reverse_inscription'		=> 'Reverse inscription',
		'reverse_mintmark'			=> 'Reverse mintmark',
		'revtypeID'					=> 'Reverse type',
		'revTypeID_qualifier'		=> 'Reverse type qualifier',
		'degree_of_wear'			=> 'Degree of wear',
		'die_axis_measurement'		=> 'Die axis',
		'die_axis_certainty'		=> 'Die axis certainty',
		'cciNumber'					=> 'CCI number',
		'allen_type'				=> 'Allen type',
		'mack_type'					=> 'Mack type',
		'bmc_type'					=> 'BMC type',
		'rudd_type'					=> 'Ancient British Coinage type',
		'va_type'					=> 'Van Arsdell type',
		'numChiab'					=> 'CHIAB number',
		'reeceID'					=> 'Reece period'	 
	);
	
	//Remove the unwanted fields from the array of fields
	foreach($remove as $rem){
		unset($fields[$rem]);
	}
	
	parent::__construct($options);

	$this->setName('configureCoinCopy');
	
	$elements = array();
	
	foreach(array_keys($fields) as $field){
		$label = $field;
	$field = new Zend_Form_Element_Checkbox($field);
	if(array_key_exists($label,$labels)){
		$clean = ucfirst($labels[$label]);
	} else {
		$clean = ucfirst($label);
	}
	
	$field->setLabel($clean)
		->setRequired(false)
		->addValidator('NotEmpty','boolean');

	$elements[] = $field;
	$this->addElement($field);
	}
	
	$this->addDisplayGroup($elements, 'details');
		//Submit button
	$submit = new Zend_Form_Element_Submit('submit');;
	$this->addElement( $submit);
	$this->details->setLegend('Choose fields: ');
    parent::init();
	}

}