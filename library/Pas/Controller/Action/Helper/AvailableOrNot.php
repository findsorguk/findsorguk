<?php

/** Action helper to get the redirect for a user upon login
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $this->_redirect( $this->_helper->loginRedirect() );
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @author Mary Chester-Kadwell <mchester-kadwell @ britishmuseum.org>
 * @version 1
 * @since 1
 * @uses Pas_User_Details
 * @uses Zend_Exception
 * @example /app/modules/users/controllers/IndexController.php
 *
 */
class Pas_Controller_Action_Helper_AvailableOrNot extends Zend_Controller_Action_Helper_Abstract
{


    protected $_restricted = array('1', '2');

    protected $_allowed = array('3', '4');

    protected $_role = NULL;

    protected $_userID = '3';

    protected $_allowedRoles = array('flos', 'admin', 'hoard', 'fa');

    protected $_notAllowedRoles = array('public', 'hero', 'research');

    protected $_veryRestricted = array(NULL, 'member');

    public function getUser()
    {
        $user = new Pas_User_Details();
        return $user;
    }

    protected function getRole()
    {
        if ($this->getUser()) {
            $this->_role = $this->getUser()->getRole();
        }
        return $this->_role;
    }

    protected function getUserId()
    {
        if (!$this->getUser()) {
            $this->_userID = $this->getUser()->getPerson()->id;
        }
        return $this->_userID;
    }

    /** Direct method for getting the user's redirect
     * @access public
     * @return string
     */
    public function direct($data)
    {
        return $this->checkAccess($data);
    }

    public function checkAccess($data)
    {
        if (is_array($data)) {
            if (array_key_exists('secwfstage', $data[0])) {
                $workflow = $data[0]['secwfstage'];
                if (!array_key_exists('objecttype', $data[0])) {
                    $data[0]['objecttype'] = 'HOARD';
                }
//                Zend_Debug::dump($this->getRole());
//                Zend_Debug::dump($workflow);
//                exit;
                // Not allowed roles, and not the creator of the record
                if (in_array($this->getRole(), $this->_notAllowedRoles) && !in_array($workflow, $this->_restricted )) {
                    return false;
                //In the restricted roles
                } elseif (in_array($this->getRole(), $this->_veryRestricted) && in_array($workflow, $this->_restricted)) {
                    $this->urlSend($data[0]['id'], $data[0]['objecttype']);
                //In allowed roles can see
                } elseif (in_array($this->getRole(), $this->_allowedRoles)) {
                    return false;
                } else {
                    $this->urlSend($data[0]['id'], $data[0]['objecttype']);
                }
            } else {
                throw new Pas_Exception('The workflow key is missing from this record', 500);
            }
        } else {
            throw new Pas_Exception('The data sent to this helper must be an array', 500);
        }
    }

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
    }

}