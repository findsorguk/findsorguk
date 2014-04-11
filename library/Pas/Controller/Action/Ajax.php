<?php
/** This controller action is primarily used for the creation of ajax responses
 *
 * This class needs total refactoring. Written in 2009 and not very good.
 * @category	Pas
 * @package		Pas_Controller
 * @subpackage	Action
 * @author 		Daniel Pett
 * @version		0.5
 * @license		GNU
 * @since		September 2009
 * @todo		Refactor completely - is this actually needed?
 */
class Pas_Controller_Action_Ajax extends Zend_Controller_Action {

	/** The error message for a missing parameter
	 *
	 * @var string
	 */
	protected $_missingParameter = 'The url is missing a parameter.
            Please check your entry point.';

	/** The error message for when nothing has been found from a db call via parameter
	 *
	 * @var string
	 */
	protected $_nothingFound = 'We can\'t find anything with that parameter.
            Please check your entry url carefully.';

	/** Form error message - is this used?
	 *
	 * @var string
	 */
	protected $_formErrors = 'Your form submission has some errors.
            Please check and resubmit.';

	/** Message when no changes made
	 *
	 * @var string
	 */
	protected $_noChange = 'No changes have been implemented';

	/** The authority object.
	 * @var object $_auth
	 */
	protected $_auth;

	public function init(){
	$this->_auth = Zend_Auth::getInstance();
	}

	/** Get a user's institution
	 */
	protected function getInstitution() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$inst = $user->institution;
	return $inst;
	}
	}
	/** Get a user's identity
	 */
 	public function getIdentityForForms(){
	if($this->_auth->hasIdentity()){
	$user = $this->_auth->getIdentity();
	$id = $user->id;
	return $id;
	} else {
	$id = '3';
	return $id;
	}
	}
	/** Get a user's role
	 */
	public function getRole(){
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}
	return $role;
	}

	/** Get a user's username
	 */
	public function getUsername() {
	return $this->_helper->identity->getPerson()->username;
	}
	/** Get a time for updating form
	 */
	public function getTimeForForms() {
	$dateTime = Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
	return $dateTime;
	}
}
