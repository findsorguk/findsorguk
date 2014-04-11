<?php
/** Form for creating and editing help topics
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class HelpForm extends Pas_Form {

public function __construct($options = null) {
	
	$authors = new Users();
	$authorOptions = $authors->getAuthors();

	parent::__construct($options);

	$this->setName('help');

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Content Title: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',60)
	->addErrorMessage('You must enter a title');

	$menuTitle = new Zend_Form_Element_Text('menuTitle');
	$menuTitle->setLabel('Menu Title: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('size',60)
	->addErrorMessage('You must enter a title');

	$author = new Zend_Form_Element_Select('author');
	$author->setLabel('Set the author of the article: ')
	->addMultiOptions(array('Choose an author' => $authorOptions))
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addErrorMessage('You must choose an author');

	$excerpt = new Zend_Form_Element_Textarea('excerpt');
	$excerpt->setLabel('Optional excerpt: ')
	->setRequired(false)
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StringTrim','StripTags'));

	$body = new Pas_Form_Element_RTE('body');
	$body->setLabel('Main body of text: ')
	->setRequired(true)
	->setAttrib('rows',10)
	->setAttrib('cols',40)
	->setAttrib('Height',400)
//	->setAttrib('ToolbarSet','Finds')
	->addFilters(array('StringTrim', 'HtmlBody', 'EmptyParagraph', 'WordChars'));

	$section = new Zend_Form_Element_Select('section');
	$section->setLabel('Set site section to appear under: ')
	->addMultiOptions(array(
	'databasehelp' => 'Database help',
	'help' => 'Site help',
	))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->setRequired(true)
	->addErrorMessage('You must choose a section for this to be filed under');

	$parentcontent = new Zend_Form_Element_Select('parent');
	$parentcontent->setLabel('Does this have a parent?: ')
	->setRequired(false)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addFilters(array('StringTrim','StripTags'));

	$metaKeywords = new Zend_Form_Element_Textarea('metaKeywords');
	$metaKeywords->setLabel('Meta keywords: ')
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StringTrim','StripTags'))
	->setRequired(true);

	$metaDescription = new Zend_Form_Element_Textarea('metaDescription');
	$metaDescription->setLabel('Meta description: ')
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StringTrim','StripTags'))
	->setRequired(true);

	$publishState = new Zend_Form_Element_Select('publishState');
	$publishState->setLabel('Publishing status: ')
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addMultiOptions(array('Please choose publish state' => array('1' => 'Draft','2' => 'Admin to review', '3' => 'Published')))->setValue(1)
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'));

	$slug = new Zend_Form_Element_Text('slug');
	$slug->setLabel('Page slug: ')
	->setAttrib('size',50)
	->addFilters(array('StringTrim','StripTags','UrlSlug'))
	->setRequired(true);

	$frontPage = new Zend_Form_Element_Checkbox('frontPage');
	$frontPage->setLabel('Appear on section\'s front page?: ')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'));

	$submit = new Zend_Form_Element_Submit('submit');


	$this->addElements(array($title,$author,$body,$section,$publishState,$excerpt,$metaKeywords,$metaDescription,$slug,$frontPage,$submit,$menuTitle ));
	$this->addDisplayGroup(array('title','menuTitle','author','body','section','publishState','excerpt','metaKeywords','metaDescription','slug','frontPage'), 'details')->removeDecorator('HtmlTag');


	$this->addDisplayGroup(array('submit'), 'submit')->removeDecorator('HtmlTag');

	$this->details->setLegend('Add new site content');
	parent::init();
	}
}