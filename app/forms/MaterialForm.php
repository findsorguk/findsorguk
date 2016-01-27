<?php
/** Form for editing and creating materials
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new MaterialForm():
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/TerminologyController.php
 *
*/
class MaterialForm extends Pas_Form {

    /** The constructor
     * @return void
     * @param array $options
     * @access public
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setName('Material');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Material type name: ')
                ->setRequired(true)
                ->setAttrib('size',60)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addErrorMessage('Please enter a title for this material type');

	$termdesc = new Pas_Form_Element_CKEditor('termdesc');
	$termdesc->setLabel('Description of material type: ')
                ->setRequired(true)
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Finds')
                ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
                ->setRequired(true)
                ->addValidator('Digits');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($term, $termdesc, $valid, $submit));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');

	$this->details->setLegend('Material details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
    }
}
