<?php

/**
 * A view helper for returning the number of applicants that have applied for
 * higher level status
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->applicants();
 * ?>
 * </code>
 * @category   Pas
 * @package View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @uses Zend View Helper Zend_View_Helper_Abstract
 * @uses model Users
 */
class Pas_View_Helper_Applicants extends Zend_View_Helper_Abstract
{

    /** The users object
     * @access protected
     * @var array
     */
    protected $_users;

    /** Get the users array
     * @access public
     * @return array
     */
    public function getUsers()
    {
        $users = new Users();
        $this->_users = $users->getNewHigherLevelRequests();
        return $this->_users;
    }

    /** Return the class
     * @access public
     * @return \Pas_View_Helper_Applicants
     */
    public function applicants()
    {
        return $this;
    }

    /** Build the html
     * @access public
     * @return string|boolean
     */
    public function _buildHtml()
    {
        $html = '';
        $url = $this->view->url(array(
                'module' => 'admin',
                'controller' => 'users',
                'action' => 'upgrades'
            ),
            null, true);
        $data = $this->getUsers();
        if ($data) {
            $html = '';
            $html .= '<li class="purple">';
            $html .= '<a href="';
            $html .= $url;
            $html .= '" title="View upgrade requests">';
            $html .= $data['0']['applicants'];
            $html .= ' applicants waiting</a></li>';
        }
        return $html;
    }

    /** Magic to string
     * @access public
     * @return type
     */
    public function __toString()
    {
        return $this->_buildHtml();
    }
}