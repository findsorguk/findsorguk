<?php
/** Form for setting up and editing provisional valuations
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $form = new ProvisionalValuationForm();
 * $form->submit->setLabel('Add valuation');
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses People
 * @example /app/modules/database/controllers/TreasureController.php
*/
class ProvisionalValuationForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {
	
	$curators = new People();
	$assigned = $curators->getValuers();

	ZendX_JQuery::enableForm($this);

	parent::__construct($options);

	$this->setName('provisionalvaluations');

	$valuerID = new Zend_Form_Element_Select('valuerID');
	$valuerID->setLabel('Valuation provided by: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('StringLength', false, array(1,25))
		->addValidator('InArray', false, array(array_keys($assigned)))
		->addMultiOptions($assigned);

	$value = new Zend_Form_Element_Text('value');
	$value->setLabel('Estimated market value: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('Float');
	
	$comments  = new Pas_Form_Element_CKEditor('comments');
	$comments->setLabel('Valuation comments: ')
		->setRequired(false)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));
	
	$dateOfValuation = new ZendX_JQuery_Form_Element_DatePicker('dateOfValuation');
	$dateOfValuation->setLabel('Valuation provided on: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('size', 20)
		->addValidator('Datetime');
	
	$submit = new Zend_Form_Element_Submit('submit');
	
	$this->addElements(array(
	$valuerID, $value, $dateOfValuation,
	$comments, $submit
	));
	
	$this->addDisplayGroup(array(
            'valuerID', 'value', 'dateOfValuation',
            'comments'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
    }
}