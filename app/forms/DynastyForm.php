<?php
/** Form for entering data about Roman dynasties
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new DynastyForm();
 * $form->details->setLegend('Add a new dynasty\'s details');
 * $form->submit->setLabel('Submit dynasty\'s details');
 * $this->view->form =$form;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @category Pas
 * @package Pas_Form
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class DynastyForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {
	
        parent::__construct($options);

	$this->setName('dynasticDetails');

	$dynasty = new Zend_Form_Element_Text('dynasty');
	$dynasty->setLabel('Dynastic name: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('NotEmpty')
                ->addErrorMessage('Come on it\'s not that hard, enter a name for this dynasty!');


	$date_from = new Zend_Form_Element_Text('date_from');
	$date_from->setLabel('Issued coins from: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
                ->addErrorMessage('You must enter a date for the start of reign');

	$date_to = new Zend_Form_Element_Text('date_to');
	$date_to->setLabel('Issued coins until: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Int')
                ->addErrorMessage('You must enter a date for the end of reign');

	$description = new Pas_Form_Element_CKEditor('description');
	$description->setLabel('Description: ')
                ->setRequired(true)
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Finds')
                ->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'))
                ->addErrorMessage('You must enter a description');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this dynasty valid?')
        	->addFilters(array('StripTags','StringTrim'));

	$submit = new Zend_Form_Element_Submit('submit');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
            $dynasty, $date_from, $date_to,
            $description, $valid, $submit, 
            $hash));
	$this->addDisplayGroup(array(
            'dynasty', 'date_from', 'date_to',
            'description','valid','submit'), 
                'details');
	parent::init();
    }
}