<?php

/** Action helper to check access to a record
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $this->_helper->availableOrNot($finds, TRUE);
 * ?>
 * </code>
 *
 * @category Pas
 * @package Controller
 * @subpackage Controller_Action
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @author Mary Chester-Kadwell <mchester-kadwell @ britishmuseum.org>
 * @version 1
 * @since 1
 * @uses Pas_User_Details
 * @uses Zend_Exception
 * @example app/modules/database/controllers/ArtefactsController.php
 *
 */
class Pas_Controller_Action_Helper_AvailableOrNot extends Zend_Controller_Action_Helper_Abstract
{

    /** Restricted workflow stages
     * @var array
     */
    protected $_restricted = array('1', '2');

    /** Globally allowed workflow stages
     * @var array
     */
    protected $_allowed = array('3', '4');

    /** Role default
     * @var null
     */
    protected $_role = NULL;

    /** Default user ID
     * @var string
     */
    protected $_userID = '3';

    /** Default institution
     * @var string
     */
    protected $_institution = 'PUBLIC';

    /** Allowed roles
     * @var array
     */
    protected $_allowedRoles = array('flos', 'admin', 'hoard', 'fa', 'treasure');

    /** Not allowed roles
     * @var array
     */
    protected $_notAllowedRoles = array('hero', 'research');

    /** Very restricted groups
     * @var array
     */
    protected $_veryRestricted = array(NULL, 'member', 'public');

    /** Get the user
     * @return \Pas_User_Details
     */
    public function getUser()
    {
        $user = new Pas_User_Details();
        return $user;
    }

    /** Get the role of the user
     * @return string
     */
    protected function getRole()
    {
        if ($this->getUser()) {
            $this->_role = $this->getUser()->getRole();
        }
        return $this->_role;
    }

    /** Get the user id of the user
     * @return int
     */
    protected function getUserId()
    {
        if ($this->getUser()) {
            if ($this->getUser()->getPerson()) {
                $this->_userID = $this->getUser()->getPerson()->id;
            }
        }
        return $this->_userID;
    }

    protected function getInstitution()
    {
        if (!$this->getUser()) {
            $this->_institution = $this->getUser()->getPerson()->institution;
        }
        return $this->_institution;
    }

    /** Direct method for checking
     * @access public
     * @return string
     */
    public function direct($data)
    {
        return $this->checkAccess($data);
    }

    /** Check if data and access okay
     * @param array $data
     */
    public function checkAccess(array $data, $debug = FALSE)
    {
        if($debug) {
            $this->debug($data);
        }
        if (is_array($data) && !empty($data)) {
            if (array_key_exists('secwfstage', $data[0])) {
                $workflow = $data[0]['secwfstage'];
                if (!array_key_exists('objecttype', $data[0])) {
                    $data[0]['objecttype'] = 'HOARD';
                } else {
                    $data[0]['objecttype'] = 'UNIDENTIFIED OBJECT';
                }
                // Not allowed roles, and not the creator of the record
	        if (in_array($this->getRole(), $this->_notAllowedRoles) && $this->getUserId() == $data[0]['createdBy']) {
                    return false;
                    //In the restricted roles and created record
                } else if (in_array($this->getRole(), $this->_notAllowedRoles) && in_array($workflow, $this->_restricted)) {
                    $this->urlSend($data[0]['id'], $data[0]['objecttype']);
                    //In the restricted roles and created record
                } else if (in_array($this->getRole(), $this->_veryRestricted) && $this->getUserId() == $data[0]['createdBy'] ||
                    $this->getUserId() == $data[0]['createdBy'] && $this->getInstitution() == $data[0]['institution']
                ) {
                    return false;
                    //In restricted roles
                } else if (in_array($this->getRole(), $this->_veryRestricted) && in_array($workflow, $this->_restricted)) {
                    $this->urlSend($data[0]['id'], $data[0]['objecttype']);
                    //In allowed roles can see
                } else if (in_array($this->getRole(), $this->_allowedRoles)) {
                    return false;
                }
            } else {
                throw new Pas_Exception('The workflow key is missing from this record', 500);
            }
        } else {
            throw new Pas_Exception_Url('This record does not exist', 404);
        }
    }

    /** Send user to a redirect
     * @param string $id
     * @param string $objectType
     * @return \Pas_Controller_Action_Helper_AvailableOrNot
     */
    public function urlSend($id, $objectType)
    {
        if ($objectType == 'HOARD') {
            $controller = 'hoards';
        } else {
            $controller = 'artefacts';
        }
        $this->getResponse()->setHttpResponseCode(401)->setRawHeader('HTTP/1.1 301 Moved Permanently');
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $redirector->gotoUrl('database/' . $controller . '/unavailable/id/' . $id);
        return $this;
    }

    /** Debug the function
     * @param array $data The record's data
     * @return \Pas_Controller_Action_Helper_AvailableOrNot
     */
    public function debug($data){
        Zend_Debug::dump($data[0]['createdBy'], 'get created by');
        Zend_Debug::dump($data[0]['institution'], 'get created by inst');
        Zend_Debug::dump($this->getRole(), 'get role');
        Zend_Debug::dump($this->getUserId(), 'get userid');
        Zend_Debug::dump($this->getInstitution(), 'get inst');
        exit;
    }
}
