<?php

/** Form for editing and creating Iron Age tribal data
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new IronAgeTribeForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class IronAgeTribeForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null)
    {

        parent::__construct($options);

        $this->setName('ironagetribes');

        $tribe = new Zend_Form_Element_Text('tribe');
        $tribe->setLabel('Tribe name: ')
            ->setRequired(true)
            ->setAttrib('size', 60)
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->addErrorMessage('You must enter a name for the tribe.');

        $description = new Pas_Form_Element_CKEditor('description');
        $description->setLabel('Description of the tribe: ')
            ->setRequired(false)
            ->setAttrib('rows', 10)
            ->setAttrib('cols', 40)
            ->setAttrib('Height', 400)
            ->setAttrib('ToolbarSet', 'Finds')
            ->addFilters(array('StringTrim', 'BasicHtml', 'EmptyParagraph', 'WordChars'));

        $valid = new Zend_Form_Element_Checkbox('valid');
        $valid->SetLabel('Is this tribe currently valid: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->addValidator('Int');

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array($tribe, $description, $valid, $submit));

        $this->addDisplayGroup(array('tribe', 'description', 'valid', 'submit'), 'details');

        parent::init();
    }
}