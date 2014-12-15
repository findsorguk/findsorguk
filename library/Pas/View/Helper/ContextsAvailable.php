<?php
/**
 * A view helper for determining which contexts are available and displaying
 * links to obtain them.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->contextsAvailable()
 * ->setContexts($contexts);
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>@
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_Url
 * @uses Zend_View_Helper_Baseurl
 */
class Pas_View_Helper_ContextsAvailable extends Zend_View_Helper_Abstract
{
    /** mime types
     * @access protected
     * @var array
     */
    protected $_response = array(
        'atom' 	=> 'application/atom+xml',
        'rss' 	=> 'application/rss+xml',
        'json' 	=> 'application/json',
        'vcf' 	=> 'text/v-card',
        'csv' 	=> 'application/csv',
        'rdf' 	=> 'application/rdf+xml',
        'xml' 	=> 'application/xml',
        'midas' => 'application/xml',
        'nuds'	=> 'application/xml',
        'ttl'	=> 'application/x-turtle',
        'n3'	=> 'application/rdf+n3',
        'qrcode'=> 'image/png',
        'zip' 	=> 'application/zip',
        'doc' 	=> 'application/msword',
        'xls' 	=> 'application/vnd.ms-excel',
        'ppt' 	=> 'application/vnd.ms-powerpoint',
        'pdf'	=> 'application/pdf',
        'gif' 	=> 'image/gif',
        'png' 	=> 'image/png',
        'jpeg' 	=> 'image/jpg',
        'jpg' 	=> 'image/jpg',
        'php' 	=> 'text/plain',
        'kml'	=> 'application/vnd.google-earth.kml+xml'
        );

    /** The contexts
     * @access protected
     * @var array
     */
    protected $_contexts = array();

    /** The front controller object
     * @access protected
     * @var object
     */
    protected $_front;

    /** The module
     * @access protected
     * @var string
     */
    protected $_module;

    /** The controller
     * @access protected
     * @var string
     */
    protected $_controller;

    /** The action
     * @access protected
     * @var string
     */
    protected $_action;

    /** Get the front controller
     * @access public
     * @return object
     */
    public function getFront() {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_front;
    }

    /** Get the module
     * @access public
     * @return string
     */
    public function getModule() {
        $this->_module = $this->getFront()->getModuleName();
        return $this->_module;
    }

    /** Get the controller
     * @access public
     * @return string
     */
    public function getController() {
        $this->_controller = $this->getFront()->getControllerName();
        return $this->_controller;
    }

    /** Get the action
     * @access public
     * @return string
     */
    public function getAction() {
        $this->_action = $this->getFront()->getActionName();
        return $this->_action;
    }

    /** Get the response array
     * @access public
     * @return array
     */
    public function getResponse() {
        return $this->_response;
    }

    /** Get the context
     * @access public
     * @return array
     */
    public function getContexts() {
        return $this->_contexts;
    }

    /** Set the context array
     * @access public
     * @param  array $contexts
     * @return \Pas_View_Helper_ContextsAvailable
     */
    public function setContexts( $contexts) {
        $this->_contexts = $contexts;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ContextsAvailable
     */
    public function contextsAvailable() {
        return $this;
    }

    /** Build the html string
     * @access public
     * @return string
     */
    public function buildHtml() {
        $html = '';

        $contexts = $this->getContexts();

        if ( is_array( $contexts ) && !empty( $contexts ) ) {
            $html .= '<div id="contexts" class="row-fluid">';
            $html .= '<h4 class="lead">Other formats</h4>';
            $html .= '<p>This page';
            $html .= ' is available in ';
            foreach ($contexts as $key => $value) {
                $url = $this->view->url(array(
                    'module' => $this->getModule(),
                    'controller' => $this->getController(),
                    'action' => $this->getAction(),
                    'format' => $value)
                        ,null,false);
                $html .= '<a href="';
                $html .= $url;
                $html .= '" title="Obtain data in ';
                $html .= $value;
                $html .= ' representation" ';
                //Don't allow kml to be indexed
                if ($value === 'kml') {
                    $html .= ' rel="nofollow" ';
                }

                $html .=  '>';
                $html .= $value;
                $html .= '</a> ';

                if (array_key_exists($value, $this->_response)) {
                    $this->view->headLink()->appendAlternate(
                            $this->view->serverUrl() . $url,
                            $this->_response[$value],
                            'Alternate representation as ' . $value
                            );
        }
            }
            $html .=' representations.</p></div>';
            
        }

        return $html;
    }

    /** The to string function
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->buildHtml();
    }
}
