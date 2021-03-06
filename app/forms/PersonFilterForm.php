<?php
/** Form for filtering personal data
 *  An example of code use:
 * 
 * <code>
 * <?php
 * $form = new PersonFilterForm();
 * ?>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Periods
 * @uses Activities
 * @todo this can probably be deleted!
*/
class PersonFilterForm extends Pas_Form {

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

	$name = new Zend_Form_Element_Text('fullname');
	$name->setLabel('Filter by name')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addErrorMessage('Come on it\'s not that hard, enter a title!')
		->setAttrib('size', 20);
	
	$organisation = new Zend_Form_Element_Text('organisation');
	$organisation->setLabel('Filter by organisation')
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addErrorMessage('Enter a valid organisation')
		->setAttrib('size', 20);;

	$organisationID = new Zend_Form_Element_Hidden('organisationID');
	$organisationID->addValidator('Alnum',false, array('allowWhiteSpace' => false))
		->addFilters(array('StripTags','StringTrim'));
				

	$county = new Zend_Form_Element_Select('county');
	$county->setLabel('Filter by county')
		->addValidator('Alpha',false, array('allowWhiteSpace' => true))
		->addFilters(array('StripTags','StringTrim'))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('StringLength', false, array(1,200))
		->addMultiOptions(array(
                    null => 'Choose county', 
                    'Available counties' => $county_options))
		->addValidator('InArray', false, array(array_keys($county_options)));

	$primary = new Zend_Form_Element_Select('primary_activity');
	$primary->setLabel('Filter by activity')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('StringLength', false, array(1,200))
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addMultiOptions(array(
                    null =>  'Choose activity', 
                    'Available activities' => $activities_options
                ))
		->addValidator('InArray', false, array(array_keys($county_options)));

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(4800);

	//Submit button 
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter');

	$this->addElements(array(
            $name, $county, $organisation,
            $organisationID, $primary, $submit,
            $hash));

	parent::init();
    }
}