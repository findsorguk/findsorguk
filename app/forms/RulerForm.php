<?php
/** Form for adding and editing ruler details etc
 * 
 * Example of use:
 * <code>
 * <?php
 * $form = new RulerForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Periods
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class RulerForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {
	
	$periods = new Periods();
	$period_options = $periods->getCoinsPeriod();
	
	parent::__construct($options);

	$this->setName('ruler');
	
	$issuer = new Zend_Form_Element_Text('issuer');
	$issuer->setLabel('Ruler or issuer name: ')
		->setRequired(true)
		->addErrorMessage('Please enter a name for this issuer or ruler.')	
		->setAttrib('size',70)
		->addFilters(array('StripTags', 'StringTrim', 'Purifier'));

	$date1 = new Zend_Form_Element_Text('date1');
	$date1->setLabel('Date issued from: ')
		->setRequired(true)
		->addErrorMessage('You must enter a date for the start of their issue.')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');

	$date2 = new Zend_Form_Element_Text('date2');
	$date2->setLabel('Date issued to: ')
		->setRequired(true)
		->addErrorMessage('You must enter a date for the end of their issue.')
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->SetLabel('Is this ruler or issuer currently valid: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Int');

	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Broad period attributed to: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose a period',
                    'Available periods' => $period_options
                ))
		->addValidator('InArray', false, array(array_keys($period_options)))
		->addErrorMessage('You must enter a period for this ruler/issuer');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
		->setTimeout(4800);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
            $issuer, $date1, $date2,
            $period, $valid, $submit, 
            $hash));
	
	$this->addDisplayGroup(array(
            'issuer','date1','date2',
            'period','valid','submit'),
                'details');
	$this->details->setLegend('Issuer or ruler details: ');
	parent::init();
    }
}