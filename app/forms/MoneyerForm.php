<?php

/** Form for editing and creating Republican Moneyers
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new MoneyerForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Periods
 * @version 1
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */
class MoneyerForm extends Pas_Form
{

    /** the constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null)
    {

        $periods = new Periods();
        $period_options = $periods->getMedievalCoinsPeriodList();


        parent::__construct($options);

        $this->setName('moneyers');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Moneyer\'s name: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim', 'Purifier'))
            ->addErrorMessage('Enter a moneyer\'s name');

        $period = new Zend_Form_Element_Select('period');
        $period->setLabel('Broad period: ')
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->setRequired(true)
            ->addFilters(array('StringTrim', 'StripTags'))
            ->addErrorMessage('You must enter a period for this type')
            ->addMultioptions(array(null => 'Choose a period', 'Available Options' => $period_options));

        $date_1 = new Zend_Form_Element_Text('date_1');
        $date_1->setLabel('Issued coins from: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->addValidator('Int')
            ->addErrorMessage('You must enter a date for the start of moneyer period');

        $date_2 = new Zend_Form_Element_Text('date_2');
        $date_2->setLabel('Issued coins until: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'))
            ->addValidator('Int')
            ->addErrorMessage('You must enter a date for the end of moneyer period');

        $appear = new Zend_Form_Element_Text('appear');
        $appear->setLabel('Appearance on coins: ')
            ->setRequired(true)
            ->addFilters(array('StripTags','StringTrim'));

        $bio = new Pas_Form_Element_CKEditor('bio');
        $bio->setLabel('Biography: ')
            ->setRequired(true)
            ->addFilters(array('StringTrim','WordChars','BasicHtml','EmptyParagraph'))
            ->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400))
            ->setAttrib('ToolbarSet','Finds')
            ->addErrorMessage('You must enter a biography');

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array($period, $name,  $date_1, $date_2, $bio, $appear, $submit));

        $this->addDisplayGroup(array('name', 'period', 'date_1', 'date_2', 'appear', 'bio', 'submit'), 'details');

        parent::init();
    }
}