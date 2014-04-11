<?php
/** Form for retrieval of passwords
*
* @category   Pas
* @package    Pas_Form
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class ForgotPasswordForm extends Pas_Form {


public function init() {

	$username = $this->addElement('Text', 'username',
            array('label' => 'Username: '));
	$username = $this->getElement('username')
	->setRequired(true)
	->addErrorMessage('You must enter a username')
	->addFilters(array('StringTrim','StripTags', 'Purifier'))
	->addValidator('Db_RecordExists', false,
	array('table' => 'users','field' => 'username'));


	$email = $this->addElement('Text', 'email',
	array('label' => 'Email Address: ', 'size' => '30'))->email;
	$email->addValidator('EmailAddress')
	->setRequired(true)
	->addFilters(array('StringTrim','StripTags'))
	->addErrorMessage('Please enter a valid address!')
	->addValidator('Db_RecordExists', false, array('table' => 'users',
  	'field' => 'email'));

	$submit = $this->addElement('submit', 'submit');
	$submit = $this->getElement('submit')
	->setLabel('Retrieve my password');

	$this->addDisplayGroup(array('username','email', 'submit'), 'details');

	$this->setLegend('Reset my password: ');
	parent::init();
	}
}