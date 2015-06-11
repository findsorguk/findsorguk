<?php
/**
 * Display some links if logged in.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->loggedInLinks();
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Auth
 * @uses Zend_View_Helper_Url
 * @author Daniel Pett <dpett@britishmuseum.org>
 */

class Pas_View_Helper_LoggedInlinks extends Zend_View_Helper_Abstract
{

    /** The auth object
     * @access protected
     * @var object
     */
    protected $_auth;

    /** Get the auth object
     * @access public
     * @return object
     */
    public function getAuth() {
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_LoggedInlinks
     */
    public function loggedInlinks() {
        return $this;
    }

    /** Build html string
     * @access public
     * @return string
     */
    public function buildHtml() {
        $html = '';
        if ($this->getAuth()->hasIdentity()) {
            $url =  $this->view->url(array(
                'module' => 'database',
                'controller'=>'artefacts',
                'action'=>'add'),
                    NULL, false);
            $html .= '<div class="btn-group"><p><a class="btn btn-small btn-success"';
            $html .= ' href="';
            $html .= $url;
            $html .=  '" title="Add a new artefact"';
            $html .= ' accesskey="a">Add new artefact</a></p></div>';
        }

        return $html;
    }

    /** Return the html string
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->buildHtml();
    }
}