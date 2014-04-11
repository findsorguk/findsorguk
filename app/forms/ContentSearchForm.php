<?php
/** Form for solr based single word search
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ContentSearchForm extends Pas_Form {
	
	public function __construct($options = null) {
	
	parent::__construct($options);

	$this->setName('contentSearch');
	
	
	$q = new Zend_Form_Element_Text('q');
	$q->setLabel('Search: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a search term');
		
//    $section = new Zend_Form_Element_Select('section');
//    $section->setLabel('Section')
//   		->setAttrib('class', 'span6 selectpicker show-menu-arrow')
//	    ->addMultiOptions(array(
//		'index' => 'Home page',
//		'info' => 'Site information',
//		'staffs' => 'Staffordshire Hoard Symposium',
//		'getinvolved' => 'Get involved',
//		'frg' => 'Voluntary recording guide',
//		'byzantinecoins' => 'Byzantine coin guide',
//		'greekromancoins' => 'Greek and Roman coin guide',
//		'conservation' => 'Conservation pages',
//		'news' => 'News',
//		'reviews' => 'Scheme reviews',
//		'reports' => 'Annual reports',
//		'treports' => 'Treasure annual reports',
//		'romancoins' => 'Roman coin guide',
//		'ironagecoins' => 'Iron Age coin guide',
//		'earlymedievalcoins' => 'Early Medieval coin guide',
//		'medievalcoins' => 'Medieval coin guide',
//		'postmedievalcoins' => 'Post Medieval coin guide',
//		'research' => 'Research',
//		'api' => 'Applications Programming Interface',
//		'databasehelp' => 'Database help',
//		'events' => 'Events',
//		'treasure' => 'Treasure',
//		'help' => 'Help section',
//		'publications' => 'Publications',
//		'database' => 'Database front page',
//		'oai' => 'OAI instructions',
//		'bronzeage' => 'Bronze Age guide',
//		'secret' => 'Britain\'s secret treasures'
//	    ));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search!');

	$this->addElements(array($q, $submit ));

	$this->addDisplayGroup(array('q', 'section', 'submit'), 'Search');
	parent::init();
	}
}