<?php
/** Form for manipulating treasure valuation data
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new FinalValuationForm();
 * $form->submit->setLabel('Add final valuation');
 * $this->view->form = $form;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/TreasureController.php
 */
class FinalValuationForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);
	
	$this->setName('finalvaluation');

	$value = new Zend_Form_Element_Text('value');
	$value->setLabel('Estimated market value: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Currency');

	$comments  = new Pas_Form_Element_CKEditor('comments');
	$comments->setLabel('Valuation comments: ')
                ->setRequired(false)
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Finds')
                ->addFilters(array('StripTags','StringTrim', 'BasicHtml','EmptyParagraph'));

	$dateOfValuation = new ZendX_JQuery_Form_Element_DatePicker('dateOfValuation');
	$dateOfValuation->setLabel('Valuation provided on: ')
                ->setRequired(true)
                ->setJQueryParam('dateFormat', 'yy-mm-dd')
                ->addFilters(array('StripTags','StringTrim'))
                ->addErrorMessage('You must enter a chase date')
                ->setAttrib('size', 20);

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)
	->setTimeout(60);

	$this->addElements(array(
            $value, $dateOfValuation, $comments, 
            $submit, $hash
	));
	
	$this->addDisplayGroup(array(
            'value',
            'dateOfValuation',
            'comments'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
    }
}