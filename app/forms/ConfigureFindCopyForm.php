<?php
/**
* Form for adding and editing primary activities for people
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ConfigureFindCopyForm extends Pas_Form {

	public function __construct($options = null) {
	$finds = new Finds();
	$schema = $finds->info();

	$fields = array_flip($schema['cols']);
	
	$remove = array(
		'id', 'secuid', 'old_findID',
		'updated', 'created', 'updatedBy',
		'createdBy', 'institution', 'secwfstage',
		'secowner', 'sectag', 'old_candidate',
		'old_finderID', 'objdate2subperiod_old', 'objdate1subperiod_old',
		'finder2ID', 'datefound2flag', 'datefound1flag'
	);
	
	$labels = array(
		'finderID'			=> 'Finder name',
		'smr_ref' 			=> 'SMR reference',
		'other_ref' 		=> 'Other reference',
		'datefound1qual' 	=> 'First date found qualifier',
		'datefound1'		=> 'First date found',
		'datefound2'		=> 'Second date found',
		'datefound2qual' 	=> 'Second date found qualifier',
		'culture'			=> 'Ascribed culture',
		'discmethod'		=> 'Discovery method',
		'disccircum'		=> 'Discovery circumstances',
		'objecttype'		=> 'Object type',
		'objecttypecert'	=> 'Object type certainty',
		'subclass'			=> 'Sub-classification',
		'objdate1cert'		=> 'Object period certainty from',
		'objdate2cert'		=> 'Object period certainty to',
		'objdate1period'	=> 'Object period from',
		'objdate2period'	=> 'Object period to',
		'objdate1subperiod'	=> 'Object sub-period from',
		'objdate2subperiod'	=> 'Object sub-period to',
		'numdate1qual'		=> 'Date from qualifier',
		'numdate2qual'		=> 'Date to qualifier',
		'numdate1'			=> 'Date from',
		'numdate2'			=> 'Date to',
		'material1'			=> 'Primary material',
		'material2'			=> 'Secondary material',
		'manmethod'			=> 'Manufacture method',
		'decmethod'			=> 'Decoration method',
		'surftreat'			=> 'Surface treatment',
		'decstyle'			=> 'Decoration style',
		'reuse_period'		=> 'Period of reuse',
		'curr_loc'			=> 'Current location',
		'recorderID'		=> 'Recorder',
		'identifier1ID'		=> 'Primary identifier',
		'identifier2ID'		=> 'Secondary identifier',
		'musaccno'			=> 'Museum accession number',
		'subs_action'		=> 'Subsequent action',
		'findofnote'		=> 'Find of note',
		'findofnotereason'	=> 'Find of note reasoning',
		'treasureID'		=> 'Treasure ID number',
	
		
		 
	);
	foreach($remove as $rem){
		unset($fields[$rem]);
	}
	
	parent::__construct($options);

	$this->setName('configureFindCopy');
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
//	$this->addDisplayGroup(array('submit'), 'button');
	$this->details->setLegend('Choose fields: ');
    parent::init();
	}

}