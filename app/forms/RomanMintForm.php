<?php
/** Form for adding and editing Roman mints
* This is one of the most important forms of the entire site.
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class RomanMintForm extends Pas_Form {

public function __construct($options = null) {

	parent::__construct($options);

	$mints = new Mints();
	$mints_options = $mints->getRomanMints();


	$this->setName('romanmints');

	$id = new Zend_Form_Element_Hidden('ID');
	$id->removeDecorator('label')
		->removeDecorator('HtmlTag');

	$name = new Zend_Form_Element_Text('name');
	$name->setLabel('Issuing mint known as: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->addErrorMessage('You must enter a mint name');

	$description = new Zend_Form_Element_TextArea('description');
	$description->setLabel('Description of mint: ')
		->addFilters(array('BasicHtml', 'StringTrim', 'EmptyParagraph'))
		->setAttribs(array('cols' => 50, 'rows' => 10))
		->setAttrib('class','expanding');

	$abbrev = new Zend_Form_Element_Text('abbrev');
	$abbrev->setLabel('Abbreviation appearing on coins: ')
		->setRequired(true)
		->addErrorMessage('You must enter an abbreviation')
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->addValidator('NotEmpty');

	$latitude = new Zend_Form_Element_Text('latitude');
	$latitude->setLabel('Latitude: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Float');

	$longitude = new Zend_Form_Element_Text('longitude');
	$longitude->setLabel('Longitude: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Float');

	$pasID = new Zend_Form_Element_Select('pasID');
	$pasID->setLabel('Corresponding database entry: ')
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($mints_options)))
		->addMultiOptions($mints_options);

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	
	$this->addElements(array(
	$id, $name, $description,
	$latitude, $longitude, $pasID,
	$abbrev, $submit, $hash));

	$this->addDisplayGroup(array(
	'name', 'description', 'abbrev',
	'pasID', 'latitude', 'longitude'),
	'details');
	$this->setLegend('Active Roman Mints');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}
