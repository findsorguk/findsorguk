<?php
/** Form for cross referencing finds liaison officers to rallies
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class AddFloRallyForm extends Pas_Form{

    /** The constructor
     * @access public
     * @param array $options
     * @return void 
     */
    public function __construct(array $options) {

	$staff = new Contacts();
	$flos = $staff->getAttending();

	parent::__construct($options);

	ZendX_JQuery::enableForm($this);
	$this->setName('addFlo');
	$flo = new Zend_Form_Element_Select('staffID');
	$flo->setLabel('Finds officer present: ')
                ->setRequired(true)
                ->addFilters(array('StringTrim','StripTags'))
                ->addValidator('Int')
                ->addMultiOptions(array(NULL => 'Choose attending officer', 
                    'Our staff members' => $flos))
                ->setAttribs(array(
                    'class' => 'input-xxlarge selectpicker show-menu-arrow'));

	$dateFrom = new ZendX_JQuery_Form_Element_DatePicker('dateFrom');
	$dateFrom->setLabel('Attended from: ')
                ->setRequired(true)
                ->setJQueryParam('dateFormat', 'yy-mm-dd')
                ->addValidator('Datetime')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->addValidator('NotEmpty')
                ->setAttrib('size', 20);

	$dateTo = new ZendX_JQuery_Form_Element_DatePicker('dateTo');
	$dateTo->setLabel('Attended to: ')
                ->setRequired(false)
                ->setJQueryParam('dateFormat', 'yy-mm-dd')
                ->addValidator('Datetime')
                ->addFilters(array('StripTags', 'StringTrim'))
                ->setAttrib('size', 20);

	$submit = new Zend_Form_Element_Submit('submit');

	$this->addElements(array($flo, $dateFrom, $dateTo, $submit));

	$this->addDisplayGroup(array('staffID', 'dateFrom', 'dateTo'), 'details');

	$this->details->setLegend('Attending Finds Officers');

	$this->addDisplayGroup(array('submit'), 'buttons');

	parent::init();
	}
}