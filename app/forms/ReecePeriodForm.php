<?php
/** Form for adding and editing Reece period data
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new ReecePeriodForm();
 * $form->submit->setLabel('Add a new Reece Period');
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class ReecePeriodForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);
	
        $this->setName('reeceperiods');

	$period_name = new Zend_Form_Element_Text('period_name');
	$period_name->setLabel('Reece Period name: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
		->setAttrib('size',60)
		->addErrorMessage('You must enter a period name');

	$description = new Zend_Form_Element_Textarea('description');
	$description->setLabel('Description of period: ')
		->setRequired(true)
		->setAttrib('cols',70)
		->setAttrib('rows',20)
		->addFilters(array('BasicHtml', 'EmptyParagraph', 'StringTrim'))
		->addErrorMessage('You must enter a description');

	$date_range = new Zend_Form_Element_Text('date_range');
	$date_range->setLabel('Date range of period: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addErrorMessage('You must enter a date range for this period');

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(
                array( $period_name, $description, $date_range, $submit, $hash)
                );

	$this->addDisplayGroup(array('period_name','description','date_range','submit'), 'details');
	
	$this->details->setLegend('Reece Periods');

        parent::init();
    }
}