<?php
/** Action admin controller; an extension of the zend controller action
 * 
 * This class allows for various functions and variables to be made 
 * available to all actions that utilise it. Probably could be stream 
 * lined.
 * @category Pas
 * @package Pas_Controller_Action_Admin
 * @subpackage Action
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @since 23 Sept 2011
 */
class Pas_Controller_Action_Admin extends Zend_Controller_Action {
    
	/**Database ID constant 
	 * 
	 */
	const  DBASE_ID = 'PAS';

	/**The secure ID instance
	 * 
	 */
	const  SECURE_ID = '001';
	
	/** Array of groups in higher level zone
	 * 
	 * @var arrayunknown_type
	 */
	protected $_higherLevel = array('admin','flos','fa','treasure'); 
	protected $_researchLevel = array('member','hero','research');
	protected $_restricted = array('public');
 	protected $_missingParameter = 'The url is missing a parameter. 
            Please check your entry point.';
	protected $_nothingFound = 'We can\'t find anything with that parameter. 
            Please check your entry url carefully.';
	protected $_formErrors = 'Your form submission has some errors. 
            Please check and resubmit.';
	protected $_noChange = 'No changes have been implemented';
	
	public function preDispatch(){
		$disabled = $this->_helper->config()->disabled->toArray();
		$module = $this->getRequest()->getModuleName();
		if(in_array($module, $disabled)){
		$this->_redirect('/error/downtime');
		}
	}
	
	public function postDispatch() {
        //$this->view->messages = $this->_flashMessenger->getMessages();
        $this->view->announcements = $this->_helper->annoucements();
        }
    
	protected function getInstitution() {
	return $this->_helper->identity->getPerson()->institution;
	}
	
 	public function getIdentityForForms() {
	return $this->_helper->identity->getIdentityForForms();
	}
	
	
	public function getUsername(){
	return $this->_helper->identity->getPerson()->username;
	}
	
	public function getRole() {
	$person = $this->_helper->identity->getPerson();
	if(!$person){
	return 'public';
	} else {
	return $person->role;
	}
	}
	
	public function getAccount() {
	return $this->_helper->identity->getPerson();
	}
	
	public function getTimeForForms() {
	return Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
	}
	
	public function secuid() {
	list($usec, $sec)= explode(" ", microtime());
	$ms=dechex(round($usec * 4080));
	while(strlen($ms) < 3) {
	$ms = '0' . $ms; 
	}
	$secuid=strtoupper(self::DBASE_ID . dechex($sec) . self::SECURE_ID.$ms);
	while(strlen($ms)<3) {
	$ms = '0' . $ms; 
	}
	$secuid=strtoupper(self::DBASE_ID . dechex($sec) . self::SECURE_ID.$ms);
	return $secuid;
	}

	public function FindUid() {
	if(!is_null($this->getAccount())) {
	$inst = $this->getAccount()->institution;
	list($usec, $sec) = explode(" ", microtime());
	$suffix =  strtoupper(substr(dechex($sec), 3) . dechex(round($usec * 8)));
	$findid = $inst . '-' . $suffix;
	return $findid;
	} else {
		throw new Pas_Exception_NotAuthorised('Institution missing');
	}
	}
	
}
