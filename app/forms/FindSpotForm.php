<?php
/** Form for manipulating findspots data
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class FindSpotForm extends Pas_Form {

public function __construct($options = null) {
	// Construct the select menu data
	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	$regionModel = new OsRegions();
	$regions = $regionModel->getRegionsID();
	
	$origins = new MapOrigins();
	$origin_options = $origins->getValidOrigins();

	$landusevalues = new Landuses();
	$landuse_options = $landusevalues->getUsesValid();

	$landusecodes = new Landuses();
	$landcodes_options = $landusecodes->getCodesValid();
	$this->addElementPrefixPath('Pas_Filter', 'Pas/Filter/', 'filter');

	parent::__construct($options);

	$this->setName('findspots');

	// Object specifics
	$countyID = new Zend_Form_Element_Select('countyID');
	$countyID->setLabel('County/Unitary Authority or Metropolitan District: ')
	->setRequired(true)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose county','Available counties' => $county_options))
	->addValidator('InArray', false, array(array_keys($county_options)))
	->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$districtID = new Zend_Form_Element_Select('districtID');
	$districtID->setLabel('District: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose district after county'))
	->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$parishID = new Zend_Form_Element_Select('parishID');
	$parishID->setLabel('Parish: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose parish after district'))
	->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$regionID = new Zend_Form_Element_Select('regionID');
	$regionID->setLabel('European region: ')
	->setRegisterInArrayValidator(false)
	->addValidator('Digits')
	->addMultiOptions(array(NULL => 'Choose region','Available regions' => $regions))
	->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'));

	$action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

	$gridref = new Zend_Form_Element_Text('gridref');
	$gridref->setLabel('Grid reference: ')
	->addValidators(array('NotEmpty','ValidGridRef'))
	->addFilters(array('StripTags', 'StringTrim', 'StringToUpper', 'StripSpaces'))
	->setAttribs(array('placeholder' => 'A grid reference is in the format SU123123', 'class' => 'span4'));

	$gridrefsrc = new Zend_Form_Element_Select('gridrefsrc');
	$gridrefsrc->setLabel('Grid reference source: ')
	->addMultioptions(array(NULL => 'Choose a grid reference source','Choose source' => $origin_options))
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('InArray', false, array(array_keys($origin_options)))
	->addValidator('Int')
	->setAttrib('class', 'span4 selectpicker show-menu-arrow');

	$gridrefcert = new Zend_Form_Element_Radio('gridrefcert');
	$gridrefcert->setLabel('Grid reference certainty: ')
	->addMultiOptions(array('1' => 'Certain','2' => 'Probably','3' => 'Possibly'))
	->setValue(1)
	->addFilters(array('StripTags', 'StringTrim'))
	->setOptions(array('separator' => ''));

	if($action === 'edit'){
	$fourFigure = new Zend_Form_Element_Text('fourFigure');
	$fourFigure->setLabel('Four figure grid reference: ')
	->addValidator('NotEmpty','ValidGridRef')
	->addValidator('Alnum')
	->addFilters(array('StripTags', 'StringTrim'))
	->disabled = true;

	$easting = new Zend_Form_Element_Text('easting');
	$easting->setLabel('Easting: ')
	->addValidator('NotEmpty','Digits')
	->addFilters(array('StripTags', 'StringTrim'))
	->disabled = true;

	$northing = new Zend_Form_Element_Text('northing');
	$northing->setLabel('Northing: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->disabled = true;

	$map10k = new Zend_Form_Element_Text('map10k');
	$map10k->setLabel('10 km map: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Alnum')
	->disabled = true;

	$map25k = new Zend_Form_Element_Text('map25k');
	$map25k->setLabel('25 km map: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Alnum')
	->disabled = true;

	$declong = new Zend_Form_Element_Text('declong');
	$declong->setLabel('Longitude: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Float')
	->disabled = true;


	$declat = new Zend_Form_Element_Text('declat');
	$declat->setLabel('Latitude: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Float')
	->disabled = true;

	$declong4 = new Zend_Form_Element_Text('fourFigureLon');
	$declong4->setLabel('Four figure longitude: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Float')
	->disabled = true;


	$declat4 = new Zend_Form_Element_Text('fourFigureLat');
	$declat4->setLabel('Four figure latitude: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Float')
	->disabled = true;
	
	$woeid = new Zend_Form_Element_Text('woeid');
	$woeid->setLabel('Where on Earth ID: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->disabled = true;

	$elevation = new Zend_Form_Element_Text('elevation');
	$elevation->setLabel('Elevation: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->disabled = true;
	
	$gridLen = new Zend_Form_Element_Text('gridlen');
	$gridLen->setLabel('Grid reference length: ')
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->disabled = true;
	}

	$depthdiscovery = new Zend_Form_Element_Select('depthdiscovery');
	$depthdiscovery->setLabel('Depth of discovery')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->addMultiOptions(array(NULL => 'Depth levels','Approximate depth' => array(
	'10' => '0 - 10cm', '20' => '10 - 20cm', '30' => '20 - 30cm',
	'40' => '30 - 40cm', '50' => '40 - 50cm',
	'60' => 'Over 60 cm')))
	->setAttrib('class', 'span4 selectpicker show-menu-arrow');

	$soiltype = new Zend_Form_Element_Select('soiltype');
	$soiltype->setLabel('Type of soil around findspot: ')
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addValidator('NotEmpty','Digits')
	->addMultiOptions(array(NULL => NULL));


	$landusevalue = new Zend_Form_Element_Select('landusevalue');
	$landusevalue->setLabel('Landuse type: ')
	->addValidators(array('NotEmpty'))
	->setRegisterInArrayValidator(false)
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Choose landuse',
            'Valid landuses' => $landuse_options))
	->setAttrib('class', 'span4 selectpicker show-menu-arrow');

	$landusecode = new Zend_Form_Element_Select('landusecode');
	$landusecode->setLabel('Specific landuse: ')
	->setRegisterInArrayValidator(false)
	->addValidators(array('NotEmpty'))
	->addFilters(array('StripTags', 'StringTrim'))
	->addMultiOptions(array(NULL => 'Specific landuse will be enabled after type'))
	->setAttrib('class', 'span4 selectpicker show-menu-arrow');


	$address = new Zend_Form_Element_Textarea('address');
	$address->setLabel('Address: ')
	->addValidators(array('NotEmpty'))
	->setAttrib('rows',5)
	->setAttrib('cols',40)
	->addFilters(array('BasicHtml', 'StringTrim', 'EmptyParagraph'))
	->setAttribs(array('placeholder' => 'This data is not shown to the public'))
	->setAttrib('class','privatedata span6');

	$postcode = new Zend_Form_Element_Text('postcode');
	$postcode->setLabel('Postcode: ')
	->addValidators(array('NotEmpty', 'ValidPostCode'))
	->addFilters(array('StripTags', 'StringTrim','StringToUpper'));

	$knownas = new Zend_Form_Element_Text('knownas');
	$knownas->setLabel('Findspot to be known as: ')
	->setAttribs(array('placeholder' => 'If you fill in this, it will hide the grid references and parish', 'class' => 'span6 privatedata'))
	->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$landownername = new Zend_Form_Element_Text('landownername');
	$landownername->setLabel('Landowner: ')
	->addValidators(array('NotEmpty'))
	->setAttribs(array(
		'placeholder' => 'This data is not shown to the public', 
		'data-provide' => 'typeahead',
		'class' => 'privatedata span6'))
	->addFilters(array('StripTags', 'StringTrim'));

	$landowner = new Zend_Form_Element_Hidden('landowner');
	$landowner->addFilters(array('StripTags', 'StringTrim'));;

	$description = new Pas_Form_Element_CKEditor('description');
	$description->setLabel('Findspot description: ')
	->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400, 'class' => 'privatedata span6'))
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$comments = new Pas_Form_Element_CKEditor('comments');
	$comments->setLabel('Findspot comments: ')
	->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400, 'class' => 'privatedata span6'))
	->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));


	$submit = new Zend_Form_Element_Submit('submit');

	

	if($action === 'edit') {
	$this->addElements(array(
	$countyID, $districtID, $parishID,
	$knownas, $description, $comments,
	$regionID, $gridref, $fourFigure,
	$easting, $northing, $map10k,
	$map25k, $declong, $declat,
	$declong4, $declat4, $gridLen,
	$woeid, $elevation, $address,
	$gridrefsrc, $gridrefcert, $depthdiscovery,
	$postcode, $landusevalue, $landusecode,
	$landownername, $landowner,	$submit,
	));
	} else {
	$this->addElements(array(
	$countyID, $districtID, $parishID,
	$knownas, $depthdiscovery, $description,
	$comments, $regionID, $gridref,
	$gridrefsrc, $gridrefcert,
	$address, $postcode, $landusevalue,
	$landusecode, $landownername, $landowner,
	$submit, ));
	}


	$this->addDisplayGroup(array(
	'countyID', 'regionID', 'districtID',
	'parishID', 'knownas',
	'address', 'postcode', 'landownername',
	'landowner'),
	'details');
	
	$this->details->setLegend('Findspot information');
	
	if($action == 'edit') {
	$this->addDisplayGroup(array(
	'gridref', 'gridrefcert', 'gridrefsrc',
	'fourFigure', 'easting', 'northing',
	'map25k', 'map10k',	'declat',
	'declong', 'fourFigureLat', 'fourFigureLon', 
	'woeid', 'elevation', 'gridlen',
	'landusevalue', 'landusecode', 'depthdiscovery',
	),'spatial');
	} else {
	$this->addDisplayGroup(array(
	'gridref','gridrefcert', 'gridrefsrc',
	'landusevalue', 'landusecode', 'depthdiscovery',
	'soiltype'), 'spatial');
	}

	$this->spatial->setLegend('Spatial information');

	$this->addDisplayGroup(array('description','comments'),'commentary');
	
	$this->commentary->setLegend('Findspot comments');

	$this->addDisplayGroup(array('submit'), 'buttons');

    parent::init();
	}
}