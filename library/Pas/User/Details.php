<?php

/**
 * A class for accessing the current user's details from the user model
 * and from auth table.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $user = new Pas_User_Details();
 * $person = $user->getPerson();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package User
 * @subpackage Details
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/ArtefactsController.php
 */
class Pas_User_Details
{

    /** The auth object
     * @access protected
     * @var \Zend_Auth
     */
    protected $_auth;

    /** Construct the auth object
     * @access public
     */
    public function __construct()
    {
        $this->_auth = Zend_Auth::getInstance();
    }

    /** Get the user's identity number
     * @access public
     * @return int
     */
    public function getIdentityForForms()
    {
        if ($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            $id = $user->id;
        } else {
            $id = 3;
        }
        return $id;
    }

    /** Get all user's details from the model
     * @access public
     * @return boolean
     */
    public function getPerson()
    {
        if ($this->_auth->hasIdentity()) {
            return $this->_auth->getIdentity();
        } else {
            return false;
        }
    }

    /** Get the user's role
     * @access public
     * @return string
     */
    public function getRole()
    {
        if ($this->_auth->hasIdentity()) {
            $user = $this->_auth->getIdentity();
            $role = $user->role;
        } else {
            $role = 'public';
        }
        return $role;
    }
}