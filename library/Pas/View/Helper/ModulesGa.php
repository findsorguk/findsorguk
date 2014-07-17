<?php
/**
 * ModulesGA helper
 *
 * A view helper for displaying available modules for querying under google
 * analytics.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->modulesGa();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo Decide whether to deprecate this function
 * @uses viewHelper Pas_View_Helper
 * @category Pas
 * @package Pas_View_Helper
 * @example /app/modules/analytics/views/scripts/content/overview.phtml
 * @uses viewHelper Pas_View_Helper_
 * @uses Zend_Controller_Front
 * @uses Zend_View_Helper_Url
 */
class Pas_View_Helper_ModulesGa extends Zend_View_Helper_Abstract {

    /** The modules available
     * @access protected
     * @var array
     */
    protected $_modules = array(
        'all'   => 'All',
        'database'  => 'Database',
        'contacts'  => 'Contacts',
        'getinvolved'   => 'Get involved',
        'romancoins'    => 'Roman coins',
        'contacts'  => 'Contacts',
        'treasure'  => 'Treasure',
        'research'  => 'Research',
        'news'  => 'News',
        'flickr'    => 'Photos',
        'conservation'	=> 'Conservation',
        'ironagecoins'	=> 'Iron Age coins',
        'medievalcoins'	=> 'Medieval coins',
        'postmedievalcoins' => 'Post Medieval coins',
        'ironagecoins' => 'Iron Age coins',
        'earlymedievalcoins' => 'Early Medieval coins'
        );

    /** The request object
     * @access public
     * @var protected
     */
    protected $_request;

    /** The requested module
     * @access protected
     * @var string
     */
    protected $_module;

    /** The requested action
     * @access protected
     * @var string
     */
    protected $_action;

    /** The requested controller
     * @access protected
     * @var string
     */
    protected $_controller;

    /** The module chosen
     * @access protected
     * @var string
     */
    protected $_moduleChoice = null;

    /** Get the chosen module
     * @access public
     * @return string
     */
    public function getModule() {
        $this->_module = $this->getRequest()->getModuleName();
        return $this->_module;
    }

    /** Get the chosen action
     * @access public
     * @return string
     */
    public function getAction() {
        $this->_action = $this->getRequest()->getActionName();
        return $this->_action;
    }

    /** Get the requested controller
     * @access public
     * @return string
     */
    public function getController() {
        $this->_controller = $this->getRequest()->getControllerName();
        return $this->_controller;
    }

    /** get the chosen module
     * @access public
     * @return string
     */
    public function getModuleChoice() {
        $this->_moduleChoice = $this->getRequest()->getParam('filter');
        return $this->_moduleChoice;
    }

    /** get the array of modules
     * @access public
     * @return array
     */
    public function getModules() {
        return $this->_modules;
    }

    /** Get the request
     * @access public
     * @return \Zend_Controller_Front
     */
    public function getRequest() {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_request;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ModulesGa
     */
    public function modulesGa() {
        return $this;
    }

    /** Create the urls
     * @access public
     * @return string
     */
    public function _createUrls() {
        $html = '<ul class="nav nav-pills">';
        foreach ($this->getModules() as $k => $v) {
            $html .= '<li class="';
            if ($this->getModuleChoice() === $k) {
                $html .= 'active';
            } elseif (is_null($this->getModuleChoice()) && $k === 'all') {
                $html .= 'active';
            }
            $html .= '"><a href="';
            if ($k != 'all') {
            $html .= $this->view->url(array(
                'module' => $this->getModule(),
                'controller' => $this->getController(),
                'action' => $this->getAction(),
                'filter' => $k),
                'default', false);
            } else {
                $html .= $this->view->url(array(
                'module' => $this->getModule(),
                'controller' => $this->getController(),
                'action' => $this->getAction()),
                'default', false);
            }
            $html .= '">' . ucfirst($v);
            $html .= '</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->_createUrls();
    }
}