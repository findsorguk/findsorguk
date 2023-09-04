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
    public function __construct(array $options = null) {
        parent::__construct($options);
        
        $this->setName('datepicker');
        
        $datefrom = new Zend_Form_Element_Text('datefrom');
        $datefrom->setLabel('Date from: ')
            ->addValidator('Regex', true, array('/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/'))
            ->setAttrib('pattern', '^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$') //HTML 5 front end validation
            ->addValidator('StringLength', true, array(2))
            ->setAttrib('oninvalid', 'this.setCustomValidity("Date must be in the format YYYY-MM-DD.")')
            ->setAttrib('onchange', 'this.setCustomValidity("")')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'));
        $datefrom->getValidator('Regex')->setMessage(
            'Date must be in the format YYYY-MM-DD.'
        );


        $dateto = new Zend_Form_Element_Text('dateto');
        $dateto->setLabel('Date to: ')
            ->addValidator('Regex', true, array('/^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$/'))
            ->setAttrib('pattern', '^(19|20)\d\d-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01])$') //HTML 5 front end validation
            ->setAttrib('oninvalid', 'this.setCustomValidity("Date must be in the format YYYY-MM-DD.")')
            ->setAttrib('onchange', 'this.setCustomValidity("")')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'));
        $dateto->getValidator('Regex')->setMessage(
            'Date must be in the format YYYY-MM-DD.'
        );


        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(4800);

        $submit = new Zend_Form_Element_Submit('submit');
        
        $this->addElements(array( $datefrom, $dateto, $submit, $hash));

        $this->setLegend('Choose your own dates: ');
        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();
    }
}