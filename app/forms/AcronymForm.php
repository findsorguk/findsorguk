<?php
/** Form for adding and editing acronym data.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */

class AcronymForm extends Pas_Form {

    /** Construct the form 
     * @access public
     * @param array $options
     * @return void 
     */
    public function __construct(array $options = null) {

        parent::__construct($options);

        $this->setName('acronym');

        $abbreviation = new Zend_Form_Element_Text('abbreviation');
        $abbreviation->setLabel('Abbreviated term: ')
                ->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addErrorMessage('Enter a term.')
                ->setAttrib('size',20);

        $expanded = new Zend_Form_Element_Text('expanded');
        $expanded->setLabel('Expanded: ')
                ->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->setAttrib('size',60);

        $valid = new Zend_Form_Element_Checkbox('valid');
        $valid->setLabel('Is this term valid?: ')
                ->setRequired(false);

        $submit = new Zend_Form_Element_Submit('submit');

        $this->addElements(array(
            $abbreviation, $expanded, $valid,
            $submit));

        $this->addDisplayGroup(
                array(
                    'abbreviation','expanded','valid'
                    ), 'details');
        $this->details->setLegend('Acronym details: ');
        $this->addDisplayGroup(array('submit'), 'buttons');
        parent::init();
    }
}