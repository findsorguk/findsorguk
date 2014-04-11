<?php
/** Form for linking images to finds
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class ImageLinkForm extends Pas_Form {

public function __construct($options = null) {

    parent::__construct($options);


    $this->setName('imagelink');



    $old_findID = new Zend_Form_Element_Text('old_findID');
    $old_findID->setLabel('Filter by find ID #')
    ->setRequired(true)
    ->setAttrib('size', 20)
    ->addFilters(array('StripTags','StringTrim'))
    ->addValidator('StringLength', false, array(1,200));

    $findID = new Zend_Form_Element_Hidden('findID');
    $findID->setRequired(true)
    ->setAttrib('size', 11)
    ->addFilters(array('StripTags','StringTrim'))
    ->addValidator('StringLength', false, array(1,200));

    //Submit button
    $submit = new Zend_Form_Element_Submit('submit');
    $submit->setLabel('Link that image');

    $this->addElements(array(
    $findID, $old_findID, $submit));

    $this->addDisplayGroup(array('old_findID','findID'), 'details');


    $this->details->setLegend('Link image: ');

    $this->addDisplayGroup(array('submit'), 'buttons');

    parent::init();

    
}
}