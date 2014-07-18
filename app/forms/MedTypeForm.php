<?php
/** Form for linking medieval types to rulers
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $form = new MedTypeForm();
 * $form->details->setLegend('Add a new type\'s details');
 * $form->submit->setLabel('Submit type details');
 * $this->view->form =$form;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/NumismaticsController.php
 * @uses Periods
 * @uses CategoriesCoins
 * @uses Rulers
 */
class MedTypeForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
	
	$periods = new Periods();
	$period_options = $periods->getMedievalCoinsPeriodList();
	
	$cats = new CategoriesCoins();
	$cat_options = $cats->getCategoriesAll();

	$rulers = new Rulers();
	$ruler_options = $rulers->getAllMedRulers();

	parent::__construct($options);
	
	$this->setName('medievaltype');

	$type = new Zend_Form_Element_Text('type');
	$type->setLabel('Coin type: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('size',60)
		->addErrorMessage('You must enter a type name.');

	$periodID = new Zend_Form_Element_Select('periodID');
	$periodID->setLabel('Medieval period: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 
                        'input-xxlarge selectpicker show-menu-arrow')
		->addErrorMessage('You must enter a period for this type')
		->addMultioptions(array(
                    null => 'Choose a period',
                    'Available periods' => $period_options
                ))
		->addValidator('InArray', false, 
                        array(array_keys($period_options)))
		->addValidator('Int');

	$rulerID = new Zend_Form_Element_Select('rulerID');
	$rulerID->setLabel('Ruler assigned: ')
		->setRequired(true)
		->setAttrib('class', 
                        'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultioptions(array(
                    null => 'Choose a ruler', 
                    'Available rulers' => $ruler_options))
		->addValidator('InArray', false, 
                        array(array_keys($ruler_options)))
		->addValidator('Int');

	$datefrom = new Zend_Form_Element_Text('datefrom');
	$datefrom->setLabel('Date in use from: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');

	$dateto = new Zend_Form_Element_Text('dateto');
	$dateto->setLabel('Date in use until: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->addValidator('Digits');
	
	$categoryID = new Zend_Form_Element_Select('categoryID');
	$categoryID->setLabel('Coin category: ')
		->setRequired(true)
		->setAttrib('class', 
                        'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags', 'StringTrim'))
		->addMultiOptions(array(
                    null => 'Choose a category',
                    'Available categories' => $cat_options
                ))
		->addValidator('InArray', false, 
                        array(array_keys($cat_options)))
		->addValidator('Int');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array(
            $type, $rulerID, $periodID,
            $categoryID, $datefrom, $dateto,
            $submit
                ));

	$this->addDisplayGroup(array(
            'periodID', 'type', 'categoryID',
            'rulerID', 'datefrom', 'dateto',
            'submit'), 'details');
	
        $this->details->setLegend('Medieval type details: ');

	parent::init();
    }
}