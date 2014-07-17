<?php
/** Form for filtering finds
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new FindFilterForm();
 * $this->view->form = $form;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/SearchController.php
 */

class FindFilterForm extends Pas_Form {
    
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {

        $periods = new Periods();
        $periodword_options = $periods->getPeriodFromWords();

        parent::__construct($options);

        $this->setName('filterfinds');

        $objecttype = new Zend_Form_Element_Text('objecttype');
        $objecttype->setLabel('Filter by object type')
                ->setRequired(false)
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('Alpha', false, array('allowWhiteSpace' => true))
                ->addErrorMessage('Come on it\'s not that hard, enter a title!')
                ->setAttrib('size', 10);

        $broadperiod = new Zend_Form_Element_Select('broadperiod');
        $broadperiod->setLabel('Filter by broadperiod')
                ->setRequired(false)
                    ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
                ->addFilters(array('StripTags','StringTrim'))
                ->addValidator('stringLength', false, array(1,200))
                ->addMultiOptions(array(NULL => NULL ,'Choose period from' => $periodword_options))
                ->addValidator('InArray', false, array(array_keys($periodword_options)));

        $bbox = new Zend_Form_Element_Text('bbox');
        $bbox->setLabel('Bounding box')
                ->setRequired(true)
                ->setErrorMessages(array('You must enter a bounding box string'))
                ->setAttrib('class','span6')
                ->setAttrib('placeholder', 
                        'For example: 33.8978,-28.0371,82.70217,74.1357')
                ->setDescription('This field takes the bottom left and top '
                        . 'right corners of a box drawn on the map. '
                        . 'These are Lat/Lon pairs, not NGRs.');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(480000);
        $this->addElement($hash);

        $this->addElements(array(
        $objecttype,  $broadperiod, $bbox));

        $this->addDisplayGroup(array('objecttype', 'broadperiod', 'bbox'), 'details');
        $this->getDisplayGroup('details')->setLegend('Search criteria');
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Filter:');
        $this->addElement($submit);
        $this->setLegend('Filter by map');

        parent::init();
    }
}