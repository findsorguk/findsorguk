<?php
/** Form for solr based single word search
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new SiteWideForm();
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
class SiteWideForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
	
        parent::__construct($options);

	$this->setName('siteWideSearch');
	
	$this->setAction('/search');
	
	$q = new Zend_Form_Element_Text('q');
	$q->setLabel('Search: ')
                ->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addErrorMessage('Please enter a search term');
		
        $section = new Zend_Form_Element_Select('section');
        $section->setLabel('Section')
                    
                ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addMultiOptions(array(
                    'database' => 'Database',
                    'content' => 'Site Contents'
                ));

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Search!');;

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);

	$this->addElements(array($q, $section, $submit, $hash ));

	$this->addDisplayGroup(array('q', 'section', 'submit'), 'Search');
	parent::init();
    }
}