<?php
/** Form for editing and creating manufacturing methodologies
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $form = new ManufacturesForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/TerminologyController.php
 */
class ManufacturesForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setName('Manufactures');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Method of manufacture term: ')
                ->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addErrorMessage('Please enter a valid title for this method!');

	$termdesc = new Pas_Form_Element_CKEditor('termdesc');
	$termdesc->setLabel('Description of manufacture method: ')
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Finds')
                ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	$valid = new Zend_Form_Element_Checkbox('valid');
	$valid->setLabel('Is this term valid?: ')
                ->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('Digits');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($term, $termdesc, $valid, $submit));

	$this->addDisplayGroup(array('term','termdesc','valid'), 'details');

	$this->details->setLegend('Method of manufacture details: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
    }
}
