<?php
/** Form for setting up and editing period specific data.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new PeriodForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/TerminologyController.php
 * @uses Periods
*/
class PeriodForm extends Pas_Form {
    
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

	$periods = new Periods();
	$period_options = $periods->getPeriodFrom();

        parent::__construct($options);

	$this->setName('period');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Period name: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim','StringToUpper'))
		->addValidator('Alpha',false, array('allowWhiteSpace' => true))
		->setAttrib('size',60)
		->addErrorMessage('You must enter a period name');

	$fromdate = new Zend_Form_Element_Text('fromdate');
	$fromdate->setLabel('Date period starts: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->addErrorMessage('You must enter a start date');

	$todate = new Zend_Form_Element_Text('todate');
	$todate->setLabel('Date period ends: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->addErrorMessage('You must enter an end date');

	$notes = new Pas_Form_Element_CKEditor('notes');
	$notes->setLabel('Period notes: ')
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Period is currently in use: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int')
		->addErrorMessage('You must enter a status');

	$parent = new Zend_Form_Element_Select('parent');
	$parent->setLabel('Period belongs to: ')
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose period to',
                    'Available periods' => $period_options))
		->addValidator('InArray', false, array(array_keys($period_options)))
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int');
		
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
		$term, $fromdate, $todate,
		$valid,	$notes, $parent,
		$submit, $hash));

	$this->addDisplayGroup(array(
		'term', 'fromdate', 'todate',
		'parent', 'notes', 'valid'),
	'details');

	$this->details->setLegend('Period details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
    }
}