<?php
/** A contextSwitch for determining how to deal with rest contexts
 * Extends default context switch and adds AMF3, XML, PHP serialization
 * 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 * @license http://URL name
 */
class Pas_Controller_Action_Helper_RestContextSwitch extends Zend_Controller_Action_Helper_ContextSwitch
{
    /** The auto serialize flag
     * @access protected
     * @var boolean
     */
    protected $_autoSerialization = true;

    /** The available adapters in the system
     * @access protected
     * @var array
     */
    protected $_availableAdapters = array(
        'json'  => 'Zend_Serializer_Adapter_Json',
        'xml'   => 'REST_Serializer_Adapter_Xml',
        'php'   => 'Zend_Serializer_Adapter_PhpSerialize'
    );

    /** The array of rest contexts to return
     * @access protected
     * @var array
     */
    protected $_rest_contexts = array(
        'json' => array(
            'suffix'    => 'json',
            'headers'   => array(
                'Content-Type' => 'application/json'
            ),

            'options' => array(
                'autoDisableLayout' => true,
            ),

            'callbacks' => array(
                'init' => 'initAbstractContext',
                'post' => 'restContext'
            ),
        ),

        'xml' => array(
            'suffix'    => 'xml',
            'headers'   => array(
                'Content-Type' => 'text/xml'
            ),

            'options' => array(
                'autoDisableLayout' => true,
            ),

            'callbacks' => array(
                'init' => 'initAbstractContext',
                'post' => 'restContext'
            ),
        ),

        'php' => array(
            'suffix'    => 'php',
            'headers'   => array(
                'Content-Type' => 'application/x-httpd-php'
            ),

            'options' => array(
                'autoDisableLayout' => true,
            ),

            'callbacks' => array(
                'init' => 'initAbstractContext',
                'post' => 'restContext'
            )
        ),

        'html' => array(
            'headers'   => array(
                'Content-Type' => 'text/html; Charset=UTF-8'
            ),

            'options' => array(
                'autoDisableLayout' => false,
            )
        )
    );

    /** Set the constructor up
     * @access public
     * @param Zend_Config $options
     */
    public function __construct($options = null) {
        if ($options instanceof Zend_Config) {
            $this->setConfig($options);
        } elseif (is_array($options)) {
            $this->setOptions($options);
        }
        if (empty($this->_contexts)) {
            $this->addContexts($this->_rest_contexts);
        }
        $this->init();
    }

    /** Get whether to auto disable the layout
     * @access public
     * @return boolean
     */
    public function getAutoDisableLayout() {
        $context = $this->_actionController->getRequest()->getParam($this->getContextParam());
        return $this->_rest_contexts[$context]['options']['autoDisableLayout'];
    }

    /** Initialise the abstract context
     * @access public
     * @return \Zend_View
     */
    public function initAbstractContext()  {
        if (!$this->getAutoSerialization()) {
            return;
        }
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
        $view = $viewRenderer->view;
        if ($view instanceof Zend_View_Interface) {
            $viewRenderer->setNoRender(true);
        }
    }

    /** Check if a rest context
     * @access public
     * @return boolean
     */
    public function restContext() {
        if (!$this->getAutoSerialization()) {
            return;
        }

        $view = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->view;

        if ($view instanceof Zend_View_Interface) {
            if (method_exists($view, 'getVars')) {
                $data = $view->getVars();

                if (count($data) !== 0) {
                    $serializer = new $this->_availableAdapters[$this->_currentContext];
                    $body = $serializer->serialize($data);

                    if ($this->_currentContext == 'xml') {
                        $stylesheet = $this->getRequest()->getHeader('X-XSL-Stylesheet');
                        if ($stylesheet !== false and !empty($stylesheet)) {
                            $body = str_replace('<?xml version="1.0"?>', sprintf('<?xml version="1.0"?><?xml-stylesheet type="text/xsl" href="%s"?>', $stylesheet), $body);
                        }
                    }

                    if ($this->_currentContext == 'json') {
                        $callback = $this->getRequest()->getParam('callback', false);

                        if ($callback !== false and !empty($callback)) {
                            $body = sprintf('%s(%s)', $callback, $body);
                        }
                        
                        $body = Zend_Json::prettyPrint($body);
                    }

                    $this->getResponse()->setBody($body);
                }
            }
        }
    }

    /** Set whether to autoserialize output
     * @access public
     * @param boolean $flag
     * @return \Pas_Controller_Action_Helper_RestContextSwitch
     */
    public function setAutoSerialization($flag) {
        $this->_autoSerialization = (bool) $flag;
        return $this;
    }

    /** Get whether to auto serialize
     * @access public
     * @return boolean
     */
    public function getAutoSerialization() {
        return $this->_autoSerialization;
    }
}