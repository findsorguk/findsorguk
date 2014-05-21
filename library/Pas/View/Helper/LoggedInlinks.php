<?php
/**
 * Display some links if logged in.
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @author Daniel Pett
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Auth
 * @uses Zend_View_Helper_Url
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
    public function getAuth()
    {
        $this->_auth = Zend_Auth::getInstance();

        return $this->_auth;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_LoggedInlinks
     */
    public function loggedInLinks()
    {
        return $this;
    }

    /** Build html string
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $html = '';
        if ($this->getAuth()->hasIdentity()) {
            $url =  $this->view->url(array(
                'module' => 'database',
                'controller'=>'artefacts',
                'action'=>'add'),
                    NULL, true
                    );

            $html .= '<div id="action"><p><a class="btn btn-large btn-success"';
            $html .= 'href="';
            $html .= $url;
            $html .-  '" title="Add a new artefact"';
            $html .= 'accesskey="a">Add new artefact</a></p></div>';
    }

        return $html;
    }

    /** Return the html string
     * @access public
     * @return type
     */
    public function __toString()
    {
        return $this->buildHtml();
    }
}
