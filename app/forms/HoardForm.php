<?php
/** Form for editing and adding hoards information
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new HoardForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example  /app/modules/database/controllers/HoardsController.php
 */
class HoardForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

        $periods = new Periods();
	$period_options = $periods->getPeriodFrom();

	parent::__construct($options);

	$this->setName('hoard');

	$term = new Zend_Form_Element_Text('term');
	$term->setLabel('Hoard title: ')
                ->setRequired(true)
		->addFilters(array('StringTrim','StripTags'))
		->addErrorMessage('You must enter a title for this hoard');

	$termdesc = new Pas_Form_Element_CKEditor('termdesc');
	$termdesc->setLabel('Description of hoard: ')
		->setRequired(true)
		->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400))
		->setAttrib('ToolbarSet','Finds')
		->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'))
		->addErrorMessage('You must enter a description for this hoard');

	$period = new Zend_Form_Element_Select('period');
	$period->setLabel('Broad period attributed to: ')
		->setRequired(true)
		->addFilters(array('StripTags', 'StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(NULL,'Choose reason' => $period_options))
		->addValidator('inArray', false, array(array_keys($period_options)))
		->addErrorMessage('You must choose a period');

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($term,$termdesc,$period,$submit));

	$this->addDisplayGroup(array('term','termdesc','period'), 'details');

        $this->addDisplayGroup(array('submit'), 'buttons');

	$this->setLegend('Hoards: ');

	$this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}