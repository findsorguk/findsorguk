<?php
/** Form for retrieval of username via email
* 
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ForgotUsernameForm extends Pas_Form {

	public function init() {


	$email = $this->addElement('Text', 'email', 
	array('label' => 'Email Address: ', 'size' => '30'))->email;
	$email->addValidator('emailAddress')
	->setRequired(true)
	->addErrorMessage('Please enter a valid address!')
	->addValidator('Db_RecordExists', false,
	array('table' => 'users', 'field' => 'email'))
	->addFilters(array('StringTrim','StripTags'));
	
	$submit = $this->addElement('submit', 'submit');
	$this->addDisplayGroup(array('email', 'submit'), 'details');
	$this->setLegend('Reset my password: ');
	parent::init();
	}
}