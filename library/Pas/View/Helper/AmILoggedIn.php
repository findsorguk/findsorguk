<?php

/**
 * A view helper for checking if a user has logged in
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->amILoggedIn();
 * ?>
 * </code>
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @author Daniel Pett <dpett @ britishmuseum.org>
 * @copyright  Copyright (c) 2014 Daniel Pett & The British Museum
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_Url
 * @uses Zend_View_Helper_HeadMeta
 * @uses Zend_View_Helper_Escape
 * @uses Zend_Auth
 */
class Pas_View_Helper_AmILoggedIn extends Zend_View_Helper_Abstract
{
    /** The auth object
     * @access protected
     * @var type
     */
    protected $_auth;

    /** Construct the auth object
     * @access public
     */
    public function getAuth()
    {
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth;
    }

    /** Work out whether user logged in and render html
     * @access public
     * @return string
     */
    public function amILoggedIn()
    {
        return $this;
    }

    /** Magic method return html function
     * @access public
     * @return function
     */
    public function __toString()
    {
        return $this->html();
    }

    /** Generate html
     * @access public
     * @return string
     * @author Daniel Pett
     */
    public function html()
    {
        if ($this->getAuth()->hasIdentity()) {
            $logoutUrl = $this->view->url(array(
                    'module' => 'users',
                    'controller' => 'account',
                    'action' => 'logout'),
                'default', true);
            $user = $this->getAuth()->getIdentity();
            $fullname = $this->view->escape(ucfirst($user->fullname));
            $string = '<div id="logmein">';
            $string .= '<p><a href="';
            $string .= $this->view->url(
                array(
                    'module' => 'users',
                    'controller' => 'account'),
                'default', true);
            $string .= '" title="View your user profile">' . $fullname;
            $string .= '</a> &raquo; <a href="' . $logoutUrl;
            $string .= '">Log out</a></p><p>Assigned role: ';
            $string .= ucfirst($user->role);
            $this->view->headMeta(ucfirst($user->fullname),
                'page-user-screen_name');

            $allowed = array('admin', 'fa', 'hoard');
            if (in_array($user->role, $allowed)) {
                $string .= '<br /><a class="btn btn-small btn-danger" href="';
                $string .= $this->view->url(array('module' => 'admin'), 'default', true);
                $string .= '">Administer site</a></p>';
            }

        } else {
            $loginUrl = $this->view->url(
                array(
                    'module' => 'users'
                ),
                'default', true);
            $register = $this->view->url(
                array(
                    'module' => 'users',
                    'controller' => 'account',
                    'action' => 'register'),
                'default', true);
            $string = '<div id="logmein">';
            $string .= '<a href="' . $loginUrl;
            $string .= '" title="Login to our database">Log in</a> | <a href="';
            $string .= $register . '" title="Register with us">Register</a>';
            $this->view->headMeta('Public User', 'page-user-screen_name');
        }
        $string .= '</div>';

        return $string;
    }
}