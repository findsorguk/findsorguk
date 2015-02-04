<?php
/** Form for filtering organisations.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new OrganisationFilterForm();
 * $this->view->form = $form;
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/OrganisationsController.php
 * @uses Periods
 * @uses PrimaryActivities
 * @uses OsCounties
 * 
*/
class OrganisationFilterForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	$periods = new Periods();
	$periodword_options = $periods->getPeriodFromWords();

	$activities = new PrimaryActivities();
	$activities_options = $activities->getTerms();
	
	$counties = new OsCounties();
	$county_options = $counties->getCountiesID();

	parent::__construct($options);

 	$this->setName('filterpeople');

	$name = new Zend_Form_Element_Text('organisation');
	$name->setLabel('Filter by name')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->setAttrib('size', 40);
	
	$contact = new Zend_Form_Element_Text('contact');
	$contact->setLabel('Filter by contact person: ')
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('Enter a valid organisation')
		->setAttrib('size', 20);
	
	$contactpersonID = new Zend_Form_Element_Hidden('contactpersonID');
	$contactpersonID->addFilters(array('StripTags','StringTrim'))
		->addValidator('Alnum');
					
	
	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->addMultiOptions(array(
                    null => 'Choose a county',
                    'Available counties' => $county_options))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')

		->addValidator('InArray', false, array(array_keys($county_options)));
	
	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter');
	
	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);
	
	$this->addElements(array(
            $name, $county, $contact,
            $contactpersonID, $submit, $hash));
	
	parent::init();
    }
}
