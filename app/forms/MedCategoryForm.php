<?php
/** Form for editing and creating medieval categories
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class MedCategoryForm extends Pas_Form {
	
public function __construct($options = null) {
	
	$periods = new Periods();
	$period_options = $periods->getMedievalCoinsPeriodList();


	parent::__construct($options);
	
	$this->setName('medievaltype');

	$category = new Zend_Form_Element_Text('category');
	$category->setLabel('Medieval coin category: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StringTrim', 'StripTags'))
		->addErrorMessage('You must enter a category name.');

	$periodID = new Zend_Form_Element_Select('periodID');
	$periodID->setLabel('Medieval period: ')
		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
		->setRequired(true)
		->addFilters(array('StringTrim', 'StripTags'))
		->addErrorMessage('You must enter a period for this type')
		->addMultioptions(array(NULL => 'Choose a period', 'Available Options' => $period_options));

	$description = new Pas_Form_Element_RTE('description');
	$description->setLabel('Description: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$submit = new Zend_Form_Element_Submit('submit');
	
	$this->addElements(array(
	$category, $description, $periodID,
	$submit));
	
	$this->addDisplayGroup(array('category','periodID','description','submit'), 'details');
	parent::init();
	}
}