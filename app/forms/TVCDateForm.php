<?php
/** Form for linking cases to a specific tvc date
 * An example of code use:
 * 
 * <code>
 * <?php
 * $form = new TvcDateForm();
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses TvcDates
 * @example  /app/modules/database/controllers/TreasureController.php
 * @version 1
 * @author Daniel Pett <dpett at britishmuseum.org>
*/
class TVCDateForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {
	
        $dates = new TvcDates();
	$list = $dates->dropdown();
	
	parent::__construct($options);
	
	$this->setName('tvcdates');
	
	$date = new Zend_Form_Element_Select('tvcID');
	$date->setLabel('Date of TVC: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addErrorMessage('You must choose a TVC date')
		->addMultiOptions(array(
                    null => 'Select a TVC',
                    'Valid dates' => $list))
		->addValidator('InArray', false, array(array_keys($list)));
	
	$submit = new Zend_Form_Element_Submit('submit');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
		
	$this->addElements(array(
	$date, $submit, $hash
	));
	
	$this->addDisplayGroup(array('tvcID'), 'details');
	
	$this->addDisplayGroup(array('submit'), 'buttons');
	
	parent::init();
    }
}
