<?php
/** Form for setting up and editing map grid reference origins.
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new OriginForm();
 * $form->details->setLegend('Grid reference origin details: ');
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/TerminologyController.php
*/

class OriginForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

	parent::__construct($options);
	
	$this->setName('origingridref');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Grid reference origin term: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('size',60)
		->addErrorMessage('Please enter a valid grid reference origin');

	$termdesc = new Zend_Form_Element_Textarea('termdesc');
	$termdesc->setLabel('Description of term: ')
		->setRequired(true)
		->addFilters(array(
                    'StripTags', 'StringTrim', 'WordChars',
                    'BasicHtml', 'EmptyParagraph'))
		->setAttrib('rows',10)
		->setAttrib('cols',80)
		->addErrorMessage('You must enter a descriptive term .');

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(true)
		->addValidator('Int')
		->addErrorMessage('You must set the status of this term');

	$submit = new Zend_Form_Element_Submit('submit');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
            $term, $termdesc, $valid,
            $submit, $hash));
	
	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');
	
	$this->details->setLegend('Ascribed culture');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
    }
}