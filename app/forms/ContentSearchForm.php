<?php
/** Form for solr based single word search
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new ContentSearchForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * 
 */
class ContentSearchForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
	
	parent::__construct($options);

	$this->setName('contentSearch');
	$q = new Zend_Form_Element_Text('q');
	$q->setLabel('Search: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a search term');
		
//    
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search!');

	$this->addElements(array($q, $submit ));

	$this->addDisplayGroup(array('q', 'section', 'submit'), 'Search');
	parent::init();
	}
}