<?php
/** Form for cross referencing rulers to denominations
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class AddDenomToRulerForm extends Pas_Form {

    /** Construct the form
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options){

        parent::__construct($options);

	$this->setName('MintToRuler');

	$denomination_id = new Zend_Form_Element_Select('denomination_id');
	$denomination_id->setLabel('Denomination: ')
                ->setRequired(true)
                ->addFilters(array('StripTags','StringTrim','StringToLower'))
                ->addValidator('Int')
                ->setAttribs(array('class'=> 'textInput'))
                ->setAttribs(array('class' => 'input-xlarge selectpicker show-menu-arrow'));

	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id->addValidator('Int');

	$period_id = new Zend_Form_Element_Hidden('period_id');
	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Add denomination');

	$this->addElements(
                array(
                    $denomination_id, $ruler_id, $period_id,
                    $submit))
                ->setLegend('Add an active denomination');
	$this->addDisplayGroup(array('denomination_id'), 'details');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}