<?php
/** Form for solr based single word search
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class CombinedForm extends Pas_FormLite {
	
//	public function __construct($options = null) {
//    parent::__construct($options);

	public function init(){
	$this->setName('combined');
	$this->setAction('/database/');

	$q = new Zend_Form_Element_Text('q');
	$q->setLabel('Search content: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span10')
		->setAttrib('placeholder', 'Try coin for example')
		->addErrorMessage('Please enter a search term');

    
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search!');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->removeDecorator('DtDdWrapper')
		->removeDecorator('HtmlTag')
		->removeDecorator('label')
		->setTimeout(4800);

	$this->addElements(array($q,  $submit, $hash ));
	parent::init();

	}
}