<?php
/** Form for adding and editing Reece period data
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $form = new VolunteerForm();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Users
 * @uses ProjectTypes
 * @example /app/modules/admin/controllers/VacanciesController.php
 * @version 1
*/
class VolunteerForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options) {
		
	$projecttypes = new ProjectTypes();
	$projectype_list = $projecttypes->getTypes();
	
	$authors = new Users();
	$authorOptions = $authors->getAuthors();
	
	parent::__construct($options);
		  
	$this->setName('activity');
	
	
	$title = new Zend_Form_Element_Text('title');
	$title->setLabel('Project title: ')
		->setRequired(true)
		->setAttrib('size',60)
		->addFilters(array('StripTags','StringTrim', 'Purifier'))
		->addErrorMessage('Choose title for the project.');
	
	$description = new Pas_Form_Element_CKEditor('description');
	$description->setLabel('Short description of project: ')
		->setRequired(true)
		->setAttribs(array('rows' => 10, 'cols' => 40, 'Height' => 400))
		->setAttrib('ToolbarSet','Basic')
		->addFilters(array('BasicHtml', 'EmptyParagraph', 'StringTrim'));
		
	$length = new Zend_Form_Element_Text('length');
	$length->setLabel('Length of project: ')
		->setAttrib('size',12)
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a duration for this project in months')
		->addValidator('Digits')
		->setDescription('Enter length in months');
	
	$managedBy = new Zend_Form_Element_Select('managedBy');
	$managedBy->setLabel('Managed by: ')
		->addMultiOptions(array('Choose an author' => $authorOptions))
		->setRequired(true)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addFilters(array('StripTags','StringTrim'))
		->addValidator('InArray', false, array(array_keys($authorOptions)))
		->addErrorMessage('You must enter a manager for this project.');
		

	$suitableFor = new Zend_Form_Element_Select('suitableFor');
	$suitableFor->setLabel('Suitable for: ')
		->addMultiOptions(array(
                    null => 'Choose type of research', 
                    'Available types' => $projectype_list
                ))
		->setRequired(true)
		->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
		->addValidator('InArray', false, array(array_keys($projectype_list)))
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter suitability for this task.');

	$location = new Zend_Form_Element_Text('location');
	$location->setLabel('Where would this be located?: ')
		->setAttrib('size',12)
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'))
		->addErrorMessage('You must enter a location for the task.');

	$valid = new Zend_Form_Element_Checkbox('status');
	$valid->setLabel('Publish this task? ')
		->setRequired(true)
		->addFilters(array('StripTags','StringTrim'));

	$submit = new Zend_Form_Element_Submit('submit');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(480);
	
	$this->addElements(array(
            $title, $description, $length,
            $valid, $managedBy, $suitableFor,
            $location, $submit, $hash));
	
	$this->addDisplayGroup(array(
            'title', 'description', 'length',
            'location', 'suitableFor', 'managedBy',
            'status','submit'), 
                'details');

	$this->details->setLegend('Activity details: ');
	
	parent::init();
    }
}