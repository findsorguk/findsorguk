<?php
/** Form for adding and editing Medieval types.
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses CategoriesCoins
 * @uses Rulers
 * 
 */
class AddMedievalTypeForm extends Pas_Form {

    /** Construct the form
     * @access public
     * @param type $options
     * @return void
     */
    public function __construct(array $options) {

	$cats = new CategoriesCoins();
	$cat_options = $cats->getCategoriesAll();

	$rulers = new Rulers();
	$ruler_options = $rulers->getAllMedRulers();

	parent::__construct($options);

	$this->setName('MedievalType');

	$type = new Zend_Form_Element_Text('type');
	$type->setLabel('Medieval type: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->setAttribs(array('class'=> 'textInput','class' => 'span8'));

	$broadperiod = new Zend_Form_Element_Select('periodID');
	$broadperiod->setLabel('Broadperiod for type: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'))
                ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'))
                ->addMultioptions(array(
                    null => 'Choose broadperiod', 
                    'Available options' => array(
                        47 => 'Early Medieval',
                        29 => 'Medieval',
                        36  => 'Post Medieval'
                        )
                ));

	$category = new Zend_Form_Element_Select('categoryID');
	$category->setLabel('Coin category: ')
                ->setAttribs(array('class'=> 'textInput'))
                ->addFilter('StringTrim')
                ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'))
                ->addMultioptions( array(
                    null => 'Choose a category', 
                    'Available options' => $cat_options))
                ->addValidator('InArray', false, array(array_keys($cat_options)));

	$ruler = new Zend_Form_Element_Select('rulerID');
	$ruler->setLabel('Ruler assigned to: ')
                ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'))
                ->addFilter('StringTrim')
                ->addMultioptions(array(
                    null => 'Choose a ruler', 
                    'Available options' => $ruler_options))
                ->addValidator('inArray', false, array(array_keys($ruler_options)));

	$datefrom = new Zend_Form_Element_Text('datefrom');
	$datefrom->setLabel('Date type in use from: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'));

	$dateto = new Zend_Form_Element_Text('dateto');
	$dateto->setLabel('Date type in use until: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim','StringToLower'));
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Submit details for medieval coin type');
	$this->addElements(array(
		$type, $broadperiod, $category,
		$ruler, $datefrom, $dateto,
		$submit))
                ->setLegend('Add an active type of Medieval coin')
                ->setMethod('post');
	parent::init();
	}
}