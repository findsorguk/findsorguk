<?php
/** Form for entering data about die axes.
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new DieAxisForm();
 * $form->submit->setLabel('Add a new die axis term to the system...');
 * $this->view->form = $form;
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class DieAxisForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);
      
	$this->setName('dieaxis');

	$die_axis_name = new Zend_Form_Element_Text('die_axis_name');
	$die_axis_name->setLabel('Die axis term: ')
                ->setRequired(true)
                ->setAttrib('size',70)
                ->addErrorMessage('Please enter a term.')
                ->addFilters(array('StripTags','StringTrim'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Die axis term is in use: ')
                ->setRequired(true)
                ->addValidator('Int')
                ->addFilters(array('StripTags','StringTrim'));

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array($die_axis_name, $valid, $submit, $hash));

	$this->addDisplayGroup(array('die_axis_name','valid'), 'details');

	$this->details->setLegend('Die axis details: ');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
    }
}