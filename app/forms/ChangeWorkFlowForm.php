<?php
/** Form for submitting an error
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new ChangeWorkflowForm();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/ArtefactsController.php
 */
class ChangeWorkFlowForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setName('workflowChange');
	$wfstage = new Zend_Form_Element_Radio('secwfstage');
	$wfstage->setRequired(true)
                ->addMultiOptions(array(
                    '1' => 'Quarantine',
                    '2' => 'Review',
                    '4' => 'Validation',
                    '3' => 'Published'))
                ->addFilters(array('StripTags', 'StringTrim'));

	$finder = new Zend_Form_Element_Checkbox('finder');
	$finder->setLabel('Inform finder of workflow change?: ');
	$finder->setUncheckedValue(null);

	$content = new Pas_Form_Element_CKEditor('content');
	$content->setLabel('Enter your comment: ')
                ->addFilter('StringTrim')
                ->setAttrib('Height',400)
                ->setAttrib('ToolbarSet','Basic')
                ->addFilters(array('StringTrim','WordChars','HtmlBody','EmptyParagraph'))
                ->addErrorMessage('Please enter something in the comments box!');

	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Change status');

	$this->addElements(array($wfstage, $finder, $content, $submit));

	$this->addDisplayGroup(array('secwfstage','finder', 'content', ), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}