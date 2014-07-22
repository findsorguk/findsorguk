<?php
/** Form for adding reverse types to rulers
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/
class AddReverseToRulerForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param type $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

	$this->setName('MintToRuler');

	$reverseID = new Zend_Form_Element_Select('reverseID');
	$reverseID->setLabel('Reverse type: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'))
                ->setAttribs(array('class' => 'span8 selectpicker show-menu-arrow'));

	$rulerID = new Zend_Form_Element_Hidden('rulerID');
	$rulerID->addValidator('Int');

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Add a reverse type for this ruler');

	$this->addElements(array($reverseID, $rulerID, $submit));
	$this->addDisplayGroup(array('reverseID'), 'details');

	$this->details->setLegend('Add an active Mint');

	$this->addDisplayGroup(array('submit'), 'buttons');

	$this->details->setLegend('Add an active reverse type');

	parent::init();
	}
}