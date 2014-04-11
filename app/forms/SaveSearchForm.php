<?php
/** Form for saving your searches
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class SaveSearchForm extends Pas_Form {

public function __construct($options = null) {
	parent::__construct($options);

	$this->setName('savesearch');

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Search title : ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',30)
		->addErrorMessage('Please enter a valid title!');

	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Description of search: ')
		->setRequired(true)
		->addFilters(array('BasicHtml', 'StringTrim', 'WordChars', 'EmptyParagraph'))
		->setAttribs(array('rows' => 10, 'cols' => 30))
		->addErrorMessage('Please enter a valid description!');

	$public = new Zend_Form_Element_Checkbox('public');
	$public->setLabel('Show this to public users?: ')
		->addErrorMessage('You must set the status of this search')
		->setDescription('By setting this option, you can show this search on your profile and allow others to make use of it');

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array($title, $description, $public, $submit, $hash));

	$this->addDisplayGroup(array('title','description','public'), 'details');
	$this->details->setLegend('Save this search');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}
