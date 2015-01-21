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


    protected $_restricted = array('1','2');

    protected $_allowed = array('3','4');

    protected $_role = NULL;

    protected $_allowedRoles = array('flos', 'admin', 'hoard', 'fa', 'member');

    protected $_notAllowedRoles = array('public', 'her', 'research');

    public function getUser()
    {
        $user = new Pas_User_Details();
        return $user;
    }

    protected function getRole()
    {
        if($this->getUser()){
            $this->_role = $this->getUser()->getRole();
        } else {
            $this->_role = NULL;
        }
        return $this->_role;
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
        if(is_array($data)){
            if(array_key_exists('secwfstage', $data[0])){
                $workflow = $data[0]['secwfstage'];

                if(!in_array($this->getRole(), $this->_allowedRoles) && in_array($workflow, $this->_restricted)){
                    $this->getResponse()->setHttpResponseCode(401)->setRawHeader('HTTP/1.1 301 Moved Permanently');
                    $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                    $redirector->gotoUrl('database/artefacts/unavailable/id/' . $data[0]['id']);
                } else {
                    return false;
                }
            } else {
                throw new Pas_Exception('The workflow key is missing from this record', 500);
            }
        } else {
            throw new Pas_Exception('The data sent to this helper must be an array', 500);
        }
    }

}