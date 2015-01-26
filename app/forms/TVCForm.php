<?php
/** Form for adding and editing TVC dates and details
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example  /app/modules/database/controllers/TvcController.php
*/
class TVCForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {
	
	ZendX_JQuery::enableForm($this);
	
	parent::__construct($options);

	$this->setName('tvcdates');

	$date = new ZendX_JQuery_Form_Element_DatePicker('date');
	$date->setLabel('Date of TVC: ')
		->setRequired(true)
		->setJQueryParam('dateFormat', 'yy-mm-dd')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a chase date')
		->setAttrib('size', 20);

	$location = new Zend_Form_Element_Text('location');
	$location->setLabel('Location of meeting: ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a location')
		->addValidator('Alnum',false,array('allowWhiteSpace' => true));

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
            $date, $location, $submit,
            $hash
	));

	$this->addDisplayGroup(array('date','location'), 'details');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
    }
}