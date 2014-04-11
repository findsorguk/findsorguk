<?php
/** Form for submitting and editing content for static pages
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ContentForm extends Pas_Form
{
public function __construct($options = null)
{
	$authors = new Users();
	$authorOptions = $authors->getAuthors();

	parent::__construct($options);

	$this->setName('addcontent');

	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Content Title: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',60)
	->addValidator('NotEmpty')
	->addErrorMessage('You must enter a title');

	$menuTitle = new Zend_Form_Element_Text('menuTitle');
	$menuTitle->setLabel('Menu Title: ')
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('size',60)
	->addValidator('NotEmpty')
	->addErrorMessage('You must enter a title');

	$author = new Zend_Form_Element_Select('author');
	$author->setLabel('Set the author of the article: ')
	->addMultiOptions(array('Choose an author' => $authorOptions))
	->setRequired(true)
	->addFilters(array('StripTags','StringTrim'))
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addValidator('NotEmpty')
	->addErrorMessage('You must choose an author');

	$excerpt = new Zend_Form_Element_Textarea('excerpt');
	$excerpt->setLabel('Optional excerpt: ')
	->setRequired(false)
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StripTags','StringTrim'));

	$body = new Pas_Form_Element_RTE('body');
	$body->setLabel('Main body of text: ')
	->setRequired(true)
	->setAttrib('rows',30)
	->setAttrib('cols',60)
	->addErrorMessage('You must enter a main body of text')
	->addFilter('HtmlBody')
	->setAttrib('Height',400)
	->addFilter('EmptyParagraph')
	->addFilter('StringTrim')
	->addFilter('WordChars');

	$section = new Zend_Form_Element_Select('section');
	$section->setLabel('Set site section to appear under: ')
	->addMultiOptions(array(
	'index' => 'Home page',
	'info' => 'Site information',
	'staffs' => 'Staffordshire Hoard Symposium',
	'getinvolved' => 'Get involved',
	'frg' => 'Voluntary recording guide',
	'byzantinecoins' => 'Byzantine coin guide',
	'greekromancoins' => 'Greek and Roman coin guide',
	'conservation' => 'Conservation pages',
	'news' => 'News',
	'reviews' => 'Scheme reviews',
	'reports' => 'Annual reports',
	'treports' => 'Treasure annual reports',
	'romancoins' => 'Roman coin guide',
	'ironagecoins' => 'Iron Age coin guide',
	'earlymedievalcoins' => 'Early Medieval coin guide',
	'medievalcoins' => 'Medieval coin guide',
	'postmedievalcoins' => 'Post Medieval coin guide',
	'research' => 'Research',
	'api' => 'Applications Programming Interface',
	'databasehelp' => 'Database help',
	'events' => 'Events',
	'treasure' => 'Treasure',
	'help' => 'Help section',
	'publications' => 'Publications',
	'database' => 'Database front page',
	'oai' => 'OAI instructions',
	'bronzeage' => 'Bronze Age guide',
        'secret' => 'Britain\'s secret treasures'))
	->setRequired(true)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->addErrorMessage('You must choose a section for this to be filed under');

	$parentcontent = new Zend_Form_Element_Select('parent');
	$parentcontent->setLabel('Does this have a parent?: ')
	->setRequired(false)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow');

	$metaKeywords = new Zend_Form_Element_Textarea('metaKeywords');
	$metaKeywords->setLabel('Meta keywords: ')
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StripTags','StringTrim'))
	->setRequired(true);

	$metaDescription = new Zend_Form_Element_Textarea('metaDescription');
	$metaDescription->setLabel('Meta description: ')
	->setAttrib('rows',5)
	->setAttrib('cols',60)
	->addFilters(array('StripTags','StringTrim'))
	->setRequired(true);

	$publishState = new Zend_Form_Element_Select('publishState');
	$publishState->setLabel('Publishing status: ')
	->addMultiOptions(array('Please choose publish state' => array('1' => 'Draft',
	'2' => 'Admin to review', '3' => 'Published')))
	->setValue(1)
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
	->setRequired(true);

	$slug = new Zend_Form_Element_Text('slug');
	$slug->setLabel('Page slug: ')
	->setAttrib('size',50)
	->addFilter('UrlSlug')
	->addFilters(array('StripTags','StringTrim'))
	->setRequired(true);


	$frontPage = new Zend_Form_Element_Checkbox('frontPage');
	$frontPage->setLabel('Appear on section\'s front page?: ')
	->addValidators(array('NotEmpty','Int'))
	->setRequired(true);


	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array(
	$title, $author, $body,
	$section, $publishState, $excerpt,
	$metaKeywords, $metaDescription,
	$slug, $frontPage, $submit,
	$menuTitle, $hash ));

	$this->addDisplayGroup(array('title', 'menuTitle', 'author',
	'body', 'section', 'publishState',
	'excerpt', 'metaKeywords', 'metaDescription',
	'slug','frontPage'), 'details');


	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Add new site content');

	parent::init();
	}

}