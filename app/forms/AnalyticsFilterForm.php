<?php
/** Form for applying segments to google analytics traffic
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Will need changing for the solr version
*/

class AnalyticsFilterForm extends Pas_Form {

public function __construct($options = null) {


    parent::__construct($options);

    $this->setName('segments');

    $segments = new Zend_Form_Element_Select('segment');
    $segments->setLabel('Apply a segment')
    ->setRequired(false)
    ->addFilters(array('StripTags','StringTrim'))
    ->setAttribs(array('class' => 'span6 selectpicker show-menu-arrow'))
    ->addMultiOptions(array(null => 'Available segments' ,'Choose a segment' => array(
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
    )))
    ;

    //Submit button
    
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Apply segment:');

    $hash = new Zend_Form_Element_Hash('csrf');
    $hash->setValue($this->_salt)
    ->setTimeout(60000);
    $this->addElement($hash);

    $this->addElements(array(
    $segments, 
    $submit)
    );

    parent::init();

    }
}