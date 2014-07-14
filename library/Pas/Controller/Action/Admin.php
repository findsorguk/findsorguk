<?php
/** Action admin controller; an extension of the zend controller action
 *
 * This class allows for various functions and variables to be made
 * available to all actions that utilise it. Probably could be stream
 * lined.
 * 
 * @category Pas
 * @package Controller_Action
 * @subpackage Admin
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @since 23 Sept 2011
 * 
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
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin', 'flos', 'fa', 'treasure');

    /** Array of roles in research level
     * @access protected
     * @var array
     */
    protected $_researchLevel = array('member', 'hero', 'research');

    /** Array of roles in restricted level
     * @access protected
     * @var type
     */
    protected $_restricted = array('public');

    /** Message for parameter absence
     * @access protected
     * @var string
     */
    protected $_missingParameter = 'The url is missing a parameter.
            Please check your entry point.';

    /** Message for no parameter found
     * @access public
     * @var string
     */
    protected $_nothingFound = 'We cannot find anything with that parameter.
            Please check your entry url carefully.';

    /** Message for form errors
     * @access protected
     * @var string
     */
    protected $_formErrors = 'Your form submission has some errors.
            Please check and resubmit.';

    /** No changes message
     * @access protected
     * @var string
     */
    protected $_noChange = 'No changes have been implemented';

    /** Pre dispatch function.
     * This checks whether a module is disabled in the config and redirects the
     * request to the disabled message.
     * @access public
     */
    public function preDispatch(){
        $disabled = $this->_helper->config()->disabled->toArray();
        $module = $this->getRequest()->getModuleName();
        if(in_array($module, $disabled)){
            $this->_redirect('/error/downtime');
	}
    }

    /** Post dispatch function
     * @access public
     */
    public function postDispatch() {
        $this->view->announcements = $this->_helper->announcements();
    }

    /** Get the institution of a user for reuse in controllers
     * @access public
     * @return string
     */
    public function getInstitution() {
	return $this->_helper->identity->getPerson()->institution;
    }

    /** Get the user's ID number for use in controllers
     * @access public
     * @return int
     */
    public function getIdentityForForms() {
	return $this->_helper->identity->getIdentityForForms();
    }

    /** Get the user's username for use in controllers
     * @access public
     * @return string
     */
    public function getUsername(){
	return $this->_helper->identity->getPerson()->username;
    }

    /** Get the user's role
     * @access public
     * @return string
     */
    public function getRole() {
	$person = $this->_helper->identity->getPerson();
	if(!$person){
            $role = 'public';
	} else {
            $role = $person->role;
	}
        return $role;
    }

    /** Get full account details for reuse
     * @access public
     * @return object
     */
    public function getAccount() {
	return $this->_helper->identity->getPerson();
    }

    /** Get current date
     * @access public
     * @return string
     */
    public function getTimeForForms() {
	return Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
    }

    /** Create a secuid
     * @access public
     * @return string
     */
    public function secuid() {
        list($usec, $sec)= explode(" ", microtime());
        $ms = dechex(round($usec * 4080));
	while(strlen($ms) < 3) {
            $ms = '0' . $ms;
	}
	return strtoupper(self::DBASE_ID . dechex($sec) . self::SECURE_ID . $ms);
    }

    /** Create an individual findID
     * @access public
     * @return type
     */
    public function FindUid() {
	list($usec, $sec) = explode(" ", microtime());
	$suffix =  strtoupper(substr(dechex($sec), 3) . dechex(round($usec * 15)));
	return $this->getInstitution() . '-' . $suffix;
    }

    /** Retrieve the page number from pagination
     * @access public
     * @return int
     */
    public function getPage(){
        $page = $this->_getParam('page');
        if(!isset($page)){
            $start = 1;
        } else {
            $start = $page ;
	}
	return $start;
    }

     /** Curl function to retrieve data from url
     * @access public
     * @param string $url
     */
    public function get( $url ){
        $useragent = new Zend_Http_UserAgent();
        $config = array(
            'adapter'   => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_POST =>  true,
                CURLOPT_USERAGENT =>  $useragent->getUserAgent(),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
            ),
	);
        $client = new Zend_Http_Client($url, $config);
	return $client->request();
    }

    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** Create the cache
     * @access public
     */
    public function init() {
        $this->_cache = Zend_Registry::get('cache');
    }
}