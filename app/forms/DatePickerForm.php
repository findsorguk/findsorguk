<?php
/** A form for picking dates
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new DatePickerForm();
 * $form->datefrom->setValue($this->_getParam('datefrom'));
 * $form->dateto->setValue($this->_getParam('dateto'));
 * $form->submit->setLabel('Search');
 * $form->setMethod('post');
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @todo convert to jquery form
 * @example /app/modules/database/controllers/StatisticsController.php 
 */
class DatePickerForm extends Pas_Form {
    
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
        parent::__construct($options);
        
        $this->setName('datepicker');
        
        $datefrom = new Zend_Form_Element_Text('datefrom');
        $datefrom->setLabel('Date from: ')
                ->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('Datetime');

        $dateto = new Zend_Form_Element_Text('dateto');
        $dateto->setLabel('Date to: ')
                ->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('Datetime');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(4800);

        $submit = new Zend_Form_Element_Submit('submit');
        
        $this->addElements(array( $datefrom, $dateto, $submit, $hash));

        $this->setLegend('Choose your own dates: ');
        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}