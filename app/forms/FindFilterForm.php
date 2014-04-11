<?php
/** Form for filtering finds
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
* @todo		  Will need changing for the solr version
*/

class FindFilterForm extends Pas_Form {

public function __construct($options = null) {

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
	->setAttrib('class', 'span6 selectpicker show-menu-arrow')
    ->addFilters(array('StripTags','StringTrim'))
    ->addValidator('stringLength', false, array(1,200))
    ->addMultiOptions(array(NULL => NULL ,'Choose period from' => $periodword_options))
    ->addValidator('InArray', false, array(array_keys($periodword_options)));


    $bbox = new Zend_Form_Element_Text('bbox');
    $bbox->setLabel('Bounding box')
    ->setRequired(true)
    ->setErrorMessages(array('You must enter a bounding box string'))
    ->setAttrib('class','span6')
    ->setAttrib('placeholder', 'For example: 33.8978,-28.0371,82.70217,74.1357')
    ->setDescription('This field takes the bottom left and top right corners of a box drawn on the map. These are Lat/Lon pairs, not NGRs.');
    
   

    $hash = new Zend_Form_Element_Hash('csrf');
    $hash->setValue($this->_salt)
    ->setTimeout(480000);
    $this->addElement($hash);

       
    $this->addElements(array(
    $objecttype,  $broadperiod, $bbox));
    
    $this->addDisplayGroup(array('objecttype', 'broadperiod', 'bbox'), 'details');
    $this->getDisplayGroup('details')->setLegend('Search criteria');
  //Submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Filter:');
    $this->addElement($submit);
    $this->setLegend('Filter by map');

    parent::init();

    }
}