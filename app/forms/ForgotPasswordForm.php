<?php
/** Form for retrieval of passwords
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $form = new ForgotPasswordForm();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Form
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/users/controllers/AccountController.php
 */

class ForgotPasswordForm extends Pas_Form {
    
    /** Initialise form
     * @access public
     * @return void
     */
    public function init() {
	$username = $this->addElement('Text', 'username',
            array('label' => 'Username: '));
	$username = $this->getElement('username')
                ->setRequired(true)
                ->addErrorMessage('You must enter a username')
                ->addFilters(array('StringTrim','StripTags', 'Purifier'));


	$email = $this->addElement('Text', 'email',
	array('label' => 'Email Address: ', 'size' => '30'))->email;
	$email->addValidator('EmailAddress')
                ->setRequired(true)
                ->addFilters(array('StringTrim','StripTags'))
                ->addErrorMessage('Please enter a valid email address');

	$submit = $this->addElement('submit', 'submit');
	$submit = $this->getElement('submit')->setLabel('Retrieve my password');

	$this->addDisplayGroup(array('username','email', 'submit'), 'details');

	$this->setLegend('Reset my password: ');
	parent::init();
    }
}