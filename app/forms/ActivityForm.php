<?php
/** Form for adding and editing primary activities for people
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class ActivityForm extends Pas_Form {

    /** Construct the form
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

        parent::__construct($options);

	$this->setName('activity');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Activity title: ')
                ->setRequired(true)
		->addFilter('StringTrim')
		->addFilter('StripTags')
		->addErrorMessage('Choose title for the activity.')
		->setAttrib('size',70);

	$termdesc = new Pas_Form_Element_CKEditor('termdesc');
	$termdesc->setLabel('Activity description: ')
		->setRequired(true)
		->setAttrib('rows',10)
		->setAttrib('cols',40)
		->setAttrib('Height',400)
		->setAttrib('ToolbarSet','Finds')
		->addFilter('StringTrim')
		->addFilter('BasicHtml')
		->addFilter('EmptyParagraph')
		->addFilter('WordChars');


	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
		->setRequired(false)
		->addValidator('NotEmpty','boolean');

		//Submit button
	$submit = new Zend_Form_Element_Submit('submit');;

	$this->addElements( array($term, $termdesc, $valid, $submit) );

	$this->addDisplayGroup(array('term','termdesc','valid','submit'), 'details');
	$this->details->setLegend('Primary activity details: ');
    parent::init();
	}

}