<?php
/** Form for entering data about degrees of wear
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new DegreeOfWearForm();	
 * $form->details->setLegend('Add a new degree of wear term');
 * $form->submit->setLabel('Submit term\'s details');
 * $this->view->form =$form;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @example /app/modules/admin/controllers/NumismaticsController.php 
 * 
 */

class DegreeOfWearForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null){

        parent::__construct($options);
        
        $this->setName('degreeofwear');

        $term = new Zend_Form_Element_Text('term');
        $term->setLabel('Decoration style term: ')
                ->setRequired(true)
                ->setAttrib('size',70)
                ->addFilters(array('StripTags','StringTrim'))
                ->addErrorMessage('Please enter a valid title for this surface treatment');

        $termdesc = new Pas_Form_Element_CKEditor('termdesc');
        $termdesc->setLabel('Description of decoration style: ')
                ->setRequired(true)
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Finds')
                ->addFilters(array('BasicHtml', 'EmptyParagraph', 'WordChars'))
                ->addErrorMessage('You must enter a description for this surface treatment');

        $valid = new Zend_Form_Element_Checkbox('valid');
        $valid->setLabel('Period is currently in use: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->addErrorMessage('You must set a status for this treatment term');

        $submit = new Zend_Form_Element_Submit('submit');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(60);

        $this->addElements(array(
            $term, $termdesc, $valid,
            $submit, $hash));

        $this->addDisplayGroup(array('term','termdesc','valid'), 'details');

        $this->details->setLegend('Surface treatment details: ');

        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}