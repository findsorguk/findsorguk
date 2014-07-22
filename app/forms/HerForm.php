<?php
/** Form for editing and adding HER signups
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new HerForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/admin/controllers/HerController.php
 */
class HerForm extends Pas_Form {
    
    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null) {

        parent::__construct($options);
	
        $this->setName('Her');
	
        $name = new Zend_Form_Element_Text('name');
	$name->setLabel('HER name: ')
                ->setRequired(true)
                ->setAttrib('size',60)
                ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
                ->addErrorMessage('Please enter an HER name');

	$contact_name = new Zend_Form_Element_Text('contact_name');
	$contact_name->setLabel('Contact name: ')
                ->setRequired(true)
                ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
                ->setAttrib('size',40)
                ->addErrorMessage('Please enter a contact name');

	$submit = new Zend_Form_Element_Submit('submit');
	$this->addElements(array($name,$contact_name,$submit));

	$this->addDisplayGroup(array('name','contact_name'), 'details');
	$this->details->setLegend('HER details: ');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
    }
	
}