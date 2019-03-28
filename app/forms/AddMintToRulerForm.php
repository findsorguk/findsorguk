<?php
/** Form for adding mints to rulers
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/
class AddMintToRulerForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);

        $this->setName('MintToRuler');

	$mint = new Zend_Form_Element_Select('mint_id');
	$mint->setLabel('Active mint: ')
                ->setRequired(true)
                ->addValidator('Int')
                ->addFilters(array('StripTags','StringTrim','StringToLower'))
                ->setAttribs(array(
                    'class' => 'input-xxlarge selectpicker show-menu-arrow'
                    ));

	$ruler_id = new Zend_Form_Element_Hidden('ruler_id');
	$ruler_id ->removeDecorator('label')
                ->addValidator('Int')
                ->addFilter('StringTrim');

	$submit = new Zend_Form_Element_Submit('submit');

	$updated = new Zend_Form_Element_Hidden('updated');
        $updated->addFilters(array('StripTags', 'StringTrim'));

	$updatedBy = new Zend_Form_Element_Hidden('updatedBy');
        $updatedBy->addFilters(array('StripTags', 'StringTrim'));

	$this->addElements(array($mint, $ruler_id,  $updated, $updatedBy, $submit));

	$this->addDisplayGroup(array('mint_id'), 'details');

	$this->details->setLegend('Add an active Mint');

	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
    }
}
