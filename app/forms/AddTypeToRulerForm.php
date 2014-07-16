<?php
/**  Form for adding a type of coin to a specific ruler
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license    GNU General Public License
 * @version 1
*/
class AddTypeToRulerForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

        parent::__construct($options);

	$this->setName('TypeToRuler');

	$type = new Zend_Form_Element_Select('type');
	$type->setLabel('Medieval coin type: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim'))
                ->setAttribs(array('class' => 'input-xxlarge selectpicker show-menu-arrow'));

	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id->setRequired(true)
                ->addValidator('Int');

        $submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($type, $ruler_id, $submit))
	->setLegend('Add a new type')
	->setMethod('post');

	parent::init();
    }
}