<?php
/** Form for adding a staff logo to a user's account
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
*/
class AddStaffLogoForm extends Pas_Form {

    /** The constructor
     * @access public
     * @param array $options
     * @return void 
     */
    public function __construct(array $options) {

        parent::__construct($options);

	$this->setAttrib('enctype', 'multipart/form-data');
	
	$this->setName('Addlogo');

	$avatar = new Zend_Form_Element_File('logo');
	$avatar->setLabel('Upload logo: ')
		->setRequired(true)
		->setDestination('./assets/logos/')
                ->addValidator('NotEmpty')
                ->addValidator('Size', false, 512000)
		->addValidator('Extension', false, 'jpeg,tif,jpg,png,gif')
                ->setMaxFileSize(512000)
		->setAttribs(array('class'=> 'textInput'))
		->addValidator('Count', false, array('min' => 1, 'max' => 1));

	$replace = new Zend_Form_Element_Checkbox('replace');
	$replace->setLabel('Replace all current logos?: ')
                ->setCheckedValue(1)
                ->addValidator('Int');

	$hash = new Zend_Form_Element_Hash('csrf');
	$hash->setValue($this->_salt)->setTimeout(60);

	//Submit button
	$submit = new Zend_Form_Element_Submit('submit');
	$submit->setLabel('Upload a logo');

	$this->addElements(array($avatar,$replace,$submit, $hash));
	$this->addDisplayGroup(array('logo','replace'), 'details');
	$this->addDisplayGroup(array('submit'), 'buttons');
	parent::init();
	}
}