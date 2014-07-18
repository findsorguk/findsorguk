<?php
/** Form for assignation by curator
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new TreasureAssignForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example  /app/modules/database/controllers/TreasureController.php
 * @uses People
*/
class TreasureAssignForm extends Pas_Form {

    /** the constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
	$curators = new People();
	$assigned = $curators->getCurators();
	
	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);
	$this->setName('actionsForTreasure');
	
	$curatorID = new Zend_Form_Element_Select('curatorID');
	$curatorID->setLabel('Curator assigned: ')
	->setRequired(true)
	->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
	->addValidator('InArray', false, array(array_keys($assigned)))
	->addMultiOptions($assigned);
	
	$chaseDate = new ZendX_JQuery_Form_Element_DatePicker('chaseDate');
	$chaseDate->setLabel('Chase date assigned: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StringTrim','StripTags'))
		->addErrorMessage('You must enter a chase date')
		->setAttrib('size', 20);
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
		
	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElements(array(
	$curatorID, $chaseDate, $submit, $hash
	));
	
	$this->addDisplayGroup(array('curatorID','chaseDate'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
    }
}