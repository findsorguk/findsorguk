<?php
/** Form for filtering user names in the admin interfaces
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $form = new UserFilterForm();
 * $this->view->form = $form;
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/admin/controllers/UsersController.php
*/
class UserFilterForm extends Pas_Form {
	
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

	parent::__construct($options);
	
	$this->setMethod('post');  
	
	$this->setName('filterusers');

	$username = new Zend_Form_Element_Text('username');
	$username->setLabel('Filter by username')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 15);

	$name = new Zend_Form_Element_Text('fullname');
	$name->setLabel('Filter by name')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('size', 20);

	$role = new Zend_Form_Element_Select('role');
	$role->setLabel('Filter by role')
		->addFilters(array('StringTrim', 'StripTags'))
		->setAttrib('class', 'input-medium selectpicker show-menu-arrow')
		->addValidator('StringLength', false, array(1,200))
		->addMultiOptions(array(
                    null => 'Choose role',
                    'Available roles' => array(
                        'admin' => 'Admin', 
                        'hero' => 'HER officer', 
                        'flos' => 'Finds Liaison',
                        'member' => 'Member', 
                        'fa' => 'Finds Adviser', 
                        'research' => 'Researcher',
                        'treasure' => 'Treasure team')
                    ));
	
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Filter');
	
	$this->addElements(array(
            $username, $name, $role,
            $submit)
	);
	 
	parent::init(); 
    }
}