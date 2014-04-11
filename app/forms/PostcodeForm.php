<?php
/** Form for solr based single word search
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class PostcodeForm extends Pas_Form
{
public function __construct($options = null){
    parent::__construct($options);


    $this->setName('solr')->removeDecorator('HtmlTag');

	$q = new Zend_Form_Element_Text('postcode');
	$q->setLabel('Search content: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttribs(array('size' =>  20, 'class' => 'postcode'));

        $thumbnail = new Zend_Form_Element_Checkbox('thumbnail');
        $thumbnail->setLabel('Only with images?')
                ->setUnCheckedValue(null);

        $distance = new Zend_Form_Element_Select('distance');
        $distance->setLabel('Distance from postcode')
   		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'span2 selectpicker show-menu-arrow')
		->addErrorMessage('Please enter a search term')
                ->addMultiOptions(array(
                    1 => '1 km', 2 => '2km',3 => '3km',
                    4 => '4km', 5 => '5km'))
		->setDescription('Distance in KM')
                ->addValidator('Int');

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(48000);

	$this->addElements(array($q, $distance, $thumbnail, $submit, $hash ));


	$this->addDisplayGroup(array('postcode', 'distance', 'thumbnail', 'submit'), 'Search');

        $this->Search->setLegend('Postcode Search: ');

	parent::init();
	}
}