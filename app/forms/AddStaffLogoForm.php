<?php
/**
* Form for adding a staff logo to a user's account
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class AddStaffLogoForm extends Pas_Form
{

public function __construct($options = null) {

parent::__construct($options);

	$this->setAttrib('enctype', 'multipart/form-data');
	
	$this->setName('Addlogo');

	$avatar = new Zend_Form_Element_File('logo');
	$avatar->setLabel('Upload logo: ')
		->setRequired(true)
		->setDestination('./images/logos/')
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