<?php
/**
* Form for adding and editing primary activities for people
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ConfigureFindSpotCopyForm extends Pas_Form {

	public function __construct($options = null) {
	$finds = new Findspots();
	$schema = $finds->info();

	$fields = array_flip($schema['cols']);
	$remove = array(
		'id','accuracy', 'secuid',
		'updated', 'created', 'updatedBy',
		'createdBy', 'institution','findID',
		'address', 'fourFigure', 'gridlen',
		'postcode', 'easting', 'northing',
		'declong', 'declat', 'fourFigureLat',
		'fourFigureLon', 'woeid', 'geonamesID',
		'osmNode', 'elevation', 'geohash', 
		'country', 'map25k', 'map10k',
		'old_occupierid', 'occupier', 'old_findspotid',
		'soiltype', 'smrref', 'otherref',
		'date'
	);
	foreach($remove as $rem){
		unset($fields[$rem]);
	}
	
	$labels = array(
		'gridref'			=> 'Grid reference',
		'gridrefsrc'		=> 'Grid reference source',
		'gridrefcert' 		=> 'Grid reference certainty',
		'knownas' 			=> 'Known as',
		'disccircum'		=> 'Discovery circumstances',
		'landusevalue'		=> 'Land use value',
		'landusecode'	 	=> 'Land use code',
		'depthdiscovery'	=> 'Depth of discovery',
		'Highsensitivity'	=> 'High sensitivity',
	);
	
	parent::__construct($options);

	$this->setName('configureFindSpotCopy');
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