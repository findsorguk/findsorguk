<?php
/** Form for creating institutions
 *
 * An example of code:
 * <code>
 * <?php
 * $form = new InstitutionForm();
 * ?>
 * </code>
 *
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/InstitutionsController.php
 *
 */
class InstitutionForm extends Pas_Form {

    /** The constructor
     * @access public
     * @return void
     * @param array $options
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setName('institution');

	$institution = new Zend_Form_Element_Text('institution');
	$institution->setLabel('Recording institution title: ')
                ->setRequired(true)
                ->setAttrib('size',60)
                ->addFilters(array('StripTags','StringTrim'))
                ->addErrorMessage('Choose title for the role.');

	$description = new Pas_Form_Element_CKEditor('description');
	$description->setLabel('Role description: ')
                ->setRequired(true)
                ->setAttrib('rows',10)
                ->setAttrib('cols',40)
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Finds')
                ->addFilters(array(
                    'StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array($institution, $description, $submit));

	$this->addDisplayGroup(array('institution','description'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}