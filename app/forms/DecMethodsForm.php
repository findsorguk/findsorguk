<?php
/** Form for entering data about decorative methods
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new DecMethodsForm();
 * $form->submit->setLabel('Add a new decoration method');
 * $this->view->form = $form;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example E:\GitHubProjects\findsorguk\app\modules\admin\controllers\TerminologyController.php 
 * 
 */
class DecMethodsForm extends Pas_Form {

    /** The constructor object
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options){

        parent::__construct($options);

	$this->setName('Decmethods');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Decoration style term: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addErrorMessage('Please enter a valid title for this decorative method!');

	$termdesc = new Pas_Form_Element_CKEditor('termdesc');
	$termdesc->setLabel('Description of decoration style: ')
                ->setRequired(false)
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Finds')
                ->addFilters(array('BasicHtml','EmptyParagraph'))
                ->addFilter('WordChars');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')->setRequired(true);

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($term, $termdesc, $valid, $submit, $hash));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Decoration method details: ');
        parent::init();
	}
}