<?php
/** A view helper for determining whether coin link should be printed
 * @category Pas
 * @package Pas_View_Helper
 * @todo streamline code
 * @todo extend the view helper for auth and config objects
 * @copyright DEJ Pett
 * @license GNU
 * @version 1
 * @since 29 September 2011
 * @author dpett
 */
class Pas_View_Helper_AddCoinLink extends Zend_View_Helper_Abstract {

    /** Array of roles with limited access
     * @access protected
     * @var array
     */
    protected $_noaccess = array('public', NULL);
    
    /** Array of roles with restricted access
     * @access protected
     * @var array
     */
    protected $_restricted = array('member','research','hero');
    
    /** The recorders array
     * @access protected
     * @var array 
     */
    protected $_recorders = array('flos');
    
    /** The array of foles with higher level access
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin','fa','treasure');
    
    /** The find ID to use
     * @access protected
     * @var string
     */
    protected $_findID;
    
    /** The institution to use
     * @access protected
     * @var string
     */
    protected $_institution;
    
    /** The secureID to query
     * @access protected
     * @var string
     */
    protected $_secuid;
    
    /** The object's broadperiod
     * @access protected
     * @var string
     */
    protected $_broadperiod;
    
    /** The created by integer
     * @access protected
     * @var int
     */
    protected $_createdBy;
    
    /** Can create
     * @access protected
     * @var string
     */
    protected $_canCreate;
    
    /** Exception text to return for missing group
     * @access protected
     * @var string
     */
    protected $_missingGroup = 'User is not assigned to a group';
    
    /** The error message to throw
     * @access protected
     * @var string
     */
    protected $_message = 'You are not allowed edit rights to this record';

    /** Get the current user to check
     * @access protected
     * @return object
     */
    protected function _getUser() {
        $person = new Pas_User_Details();
        return $person->getPerson();
    }

    /** Function to check whether the institution of creator == user's
     * @access protected
     * @return boolean
     */
    protected function _checkInstitution() {
        if($this->_institution === $this->_getUser()->institution){
            return true;
        } else {
            return false;
	}
    }

    /** Function to check creator of record against user's id
     * @access protected
     * @return boolean
     */
    protected function _checkCreator( ) {
        $userid = (int)$this->_getUser()->id;
        if($this->_createdBy === $userid){
            return true;

        } else {
            return false;
        }
    }

    /** Set the find ID
     * @access public
     * @param int $findID
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setFindID( int $findID ) {
        if(is_int($findID)){
            $this->_findID = $findID;

        } else {
            throw new Zend_Exception('The find ID must be an integer', 500);
	}
        return $this;
    }

    /** Function to set the secuid
     * @access public
     * @param string $secuid
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setSecUid( string $secuid ) {
        if(is_string($secuid)){
            $this->_secuid = $secuid;
        } else {
            throw new Zend_Exception('The secure id set must be a string', 500);
	}
        return $this;
    }

    /** Function to set the broadperiod
     * @access public
     * @param string $broadperiod
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setBroadperiod( string $broadperiod ) {
        if(is_string($broadperiod)){
            $this->_broadperiod = $broadperiod;

        } else {
            throw new Zend_Exception('The broadperiod set must be a string', 500);
	}
        return $this;
    }

    /** Function to set the institution
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setInstitution( string $institution ) {
        if(is_string($institution)){
            $this->_institution = $institution;
        } else {
            throw new Zend_Exception('The institution must be a string', 500);
	}
	return $this;
    }

    /** Function to set created by
     * @access public
     * @param int $createdBy
     * @return \Pas_View_Helper_AddCoinLink
     * @throws Zend_Exception
     */
    public function setCreatedBy( int $createdBy ) {
        if(is_int($createdBy)){
            $this->_createdBy = $createdBy;
        } else {
            throw new Zend_Exception('The creator must be an integer', 500);
	}
	return $this;
    }

    /** Function to check that all parameters are set
     * @access private
     * @return boolean
     * @throws Zend_Exception
     */
    private function _checkParameters() {
        $parameters = array(
            $this->_broadperiod,
            $this->_createdBy,
            $this->_findID,
            $this->_secuid);
        foreach($parameters as $parameter){
            if( is_null( $parameter ) ){
                throw new Zend_Exception('A parameter is missing');
            }
            }
            return true;
    }

    /** Function to run internal checks
     * @access private
     * @return \Pas_View_Helper_AddCoinLink
     */
    private function _performChecks(){
        $user = $this->_getUser();
	if($user) {
            $role = $user->role;
        } else {
            $role = null;
	}
        if( in_array( $role, $this->_restricted ) ) {
            if( ( $this->_checkCreator() && !$this->_checkInstitution() )
                    || ( $this->_checkCreator() && $this->_checkInstitution() ) ) {
                $this->_canCreate = true;
                }
                    } else if(in_array($role,$this->_higherLevel)) {
                        $this->_canCreate = true;
                    } else if(in_array($role,$this->_recorders)){
                        if( ( $this->_checkCreator() && !$this->_checkInstitution() )
                                || ( $this->_checkCreator() && $this->_checkInstitution() )
                                || ( !$this->_checkCreator() && $this->_checkInstitution() )
                                || ( !$this->_checkCreator() && $this->_institution === 'PUBLIC' ) ) {
                            $this->_canCreate = true;
                                }
                                } else {
                                    $this->_canCreate = false;
                                }
    return $this;
    }

    /** Function to add the coin link html
     * @access public
     * @return \Pas_View_Helper_AddCoinLink
     */
    public function addCoinLink() {
        return $this;

    }

    /** Function to return the html
     * @todo might be worth moving the html to a partial
     * @access private
     * @return string
     */
    private function _buildHtml() {
        $this->_checkParameters();
        $this->_performChecks();
        if($this->_canCreate){
            $params = array(
                'module' => 'database',
		'controller' => 'coins',
		'action' => 'add',
		'broadperiod' => $this->_broadperiod,
		'findID' => $this->_secuid,
		'returnID' => $this->_findID
	);
	$url = $this->view->url($params,NULL,TRUE);
	$string = '<a class="btn btn-primary" href="' . $url . '" title="Add '
	. $this->_broadperiod . ' coin data" accesskey="m">Add ' . $this->_broadperiod
	.' coin data</a>';
        return $string;

        } else {
            return '';
	}
    }

    /** Function magic method to return string
     * @access public
     * @return string function
     */
    public function __toString(){
        return $this->_buildHtml();
    }
}
