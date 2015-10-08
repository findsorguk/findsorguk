<?php
/** Form for manipulating numismatic mint data
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new MintForm();
 * $form->submit->setLabel('Add a new mint to the system...');
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Periods
 * @example /app/modules/admin/controllersNumismaticsController.php
 * @version 1
*/
class MintForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	$periods = new Periods();
	$period_actives = $periods->getMintsActive();

	parent::__construct($options);

	$this->setName('mint');

	$mint_name = new Zend_Form_Element_Text('mint_name');
	$mint_name->setLabel('Mint name: ')
		->setRequired(true)
		->addValidator('Alnum',false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size',70);

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->SetLabel('Is this ruler or issuer currently valid: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Int');

	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null => 'Choose period',
                    'Available periods:' => $period_actives
                ))
		->addValidator('InArray', false, array(array_keys($period_actives)))
		->addErrorMessage('You must enter a period for this mint');

		//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	$this->addElements(array(
	$mint_name, $valid, $period, $hash,
	$submit));

	$this->addDisplayGroup(array('mint_name', 'period', 'valid',
	'submit'), 'details');

	$this->details->setLegend('Mint details: ');

	parent::init();
	}
}