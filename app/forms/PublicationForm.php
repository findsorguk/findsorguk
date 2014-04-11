<?php
/** Form for setting up and editing publications data
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PublicationForm extends Pas_Form {

public function __construct($options = null) {
	$types = new Publicationtypes();
	$type_options = $types->getTypes();

	parent::__construct($options);


	$this->setName('publication');

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Publication title: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50)
		->addErrorMessage('Please enter a publication title.');

	$authors = new Zend_Form_Element_Text('authors');
	$authors->setLabel('Author names: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50)
		->addErrorMessage('You must enter either an author\'s or an editor\'s name.');

	$editors = new Zend_Form_Element_Text('editors');
	$editors->setLabel('Editor names: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',50);

	$publisher = new Zend_Form_Element_Text('publisher');
	$publisher->setLabel('Publisher: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(true)
		->setAttrib('size',50)
		->addErrorMessage('You must enter a publisher.');

	$publication_place = new Zend_Form_Element_Text('publication_place');
	$publication_place->setLabel('Publication place: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(true)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->setAttrib('size',70)
		->addErrorMessage('You must enter place of publication.');

	$publication_year = new Zend_Form_Element_Text('publication_year');
	$publication_year->setLabel('Publication year: ')
		->setRequired(true)
		->addValidator('Digits')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',20)
		->addErrorMessage('You must enter year of publication.');

	$vol_no = new Zend_Form_Element_Text('vol_no');
	$vol_no->setLabel('Volume number: ')
		->setRequired(false)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->setAttrib('size',20)
		->addFilters(array('StripTags','StringTrim'));

	$edition = new Zend_Form_Element_Text('edition');
	$edition->setLabel('Edition: ')
		->setRequired(false)
		->addValidator('Alnum',false,array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',20);

	$in_publication = new Zend_Form_Element_Text('in_publication');
	$in_publication->setLabel('In publication: ')
		->addFilters(array('StripTags','StringTrim'))
		->setRequired(false)
		->setAttrib('size',50);

	$publication_type = new Zend_Form_Element_Select('publication_type');
	$publication_type->setLabel('Publication type: ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL,'Choose reason' => $type_options))
		->addValidator('InArray', false, array(array_keys($type_options)));

	$ISBN = new Zend_Form_Element_Text('ISBN');
	$ISBN->setLabel('ISBN (allows people to look it up on Amazon): ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',40)
		->addValidator('Isbn');

        $BIAB = new Zend_Form_Element_Text('biab');
	$BIAB->setLabel('British and Irish Archaeological Bibliography number:')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',40);

        $DOI = new Zend_Form_Element_Text('doi');
	$DOI->setLabel('DOI (Digital Object Identifier): ')
		->setRequired(false)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',40);

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
	$title, $authors, $publisher,
	$publication_place,	$publication_year, 	$vol_no,
	$edition,$in_publication, $editors,
	$publication_type, $ISBN, $BIAB, $DOI, $submit)
	);

	$this->addDisplayGroup(array(
	'title','authors','publisher',
	'publication_place','publication_year','vol_no',
	'edition','in_publication','editors',
	'publication_type','ISBN', 'biab', 'doi'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Publication details: ');
	parent::init();
	}
}