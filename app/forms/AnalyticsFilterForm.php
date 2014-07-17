<?php
/** Form for applying segments to google analytics traffic
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 */

class AnalyticsFilterForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     */
    public function __construct(array $options) {

        parent::__construct($options);

        $this->setName('segments');

        $segments = new Zend_Form_Element_Select('segment');
        $segments->setLabel('Apply a segment')
                ->setRequired(false)
                ->addFilters(array('StripTags','StringTrim'))
                ->setAttribs(array(
                    'class' => 'input-xxlarge selectpicker show-menu-arrow'))
                ->addMultiOptions(array(
                    null => 'Available segments' ,
                    'Choose a segment' => array(
                        'allvisits' => 'All visits (default)',
                        'newvisitors' => 'New visitors',
                        'returning' => 'Returning visitors',
                        'paidsearch' => 'Paid search (we do not do this!)',
                        'unpaidsearch' => 'Unpaid search traffic',
                        'searchtraffic' => 'Search traffic',
                        'directtraffic' => 'Direct traffic',
                        'referredtraffic' => 'Referred traffic',
                        'conversions' => 'Conversion visits',
                        'mobiles' => 'Mobile traffic',
                        'nobounces' => 'Visits without a bounce',
                        'tablets' => 'Tablet traffic'
                        )));

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Apply segment:');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(60000);
        $this->addElement($hash);
        $this->addElements(array( $segments, $submit) );

        parent::init();
    }
}