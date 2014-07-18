<?php
/** Form for creating and editing coin classification data
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new CoinClassForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/NumismaticsController.php
 * @uses Periods
 */
class CoinClassForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

	$periods = new Periods();
	$period_actives = $periods->getCoinsPeriod();

	parent::__construct($options);

	$this->setName('coinsclass');

	$referenceName = new Zend_Form_Element_Text('referenceName');
	$referenceName->setLabel('Reference volume title: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->setAttrib('size',60);

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->SetLabel('Is this volume currently valid: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'));

	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Period: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('inArray', false, array(array_keys($period_actives)))
		->addMultiOptions(array(NULL=> NULL,'Choose period:' => $period_actives))
		->addErrorMessage('You must enter a period for this mint');

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($referenceName, $valid, $period, $submit));

	$this->addDisplayGroup(array('referenceName','period','valid'), 'details');

	$this->details->setLegend('Mint details: ');

	$this->addDisplayGroup(array('submit'),'buttons');

	parent::init();
	}
}