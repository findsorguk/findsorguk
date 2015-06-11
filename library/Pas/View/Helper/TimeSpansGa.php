<?php

/**
 * A view helper for rendering several different time spans for interfacing
 * with Google Analytics api.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->timeSpansGa()->setTimeSpan('lastmonth');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper * @uses viewHelper Pas_View_Helper_
 * @example /app/modules/analytics/views/scripts/content/page.phtml
 */
class Pas_View_Helper_TimeSpansGa extends Zend_View_Helper_Abstract
{

    /** The time span ranges
     * @access protected
     * @var array
     */
    protected $_timespans = array(
        'today' => 'today',
        'yesterday' => 'yesterday',
        'this week' => 'thisweek',
        'last week' => 'lastweek',
        'this month' => 'thismonth',
        'last month' => 'lastmonth',
        'this year' => 'thisyear',
        'last year' => 'lastyear'
    );

    /** The request
     * @access protected
     * @var \Zend_Controller_Front
     */
    protected $_request;

    /** The module
     * @access protected
     * @var string
     */
    protected $_module;

    /** The action
     * @access protected
     * @var string
     */
    protected $_action;

    /** The controller
     * @access protected
     * @var string
     */
    protected $_controller;

    /** The time span by default
     * @access protected
     * @var type
     */
    protected $_timeSpan = 'thisweek';

    /** Get the module
     * @access public
     * @return string
     */
    public function getModule()
    {
        $this->_module = $this->getRequest()->getModuleName();
        return $this->_module;
    }

    /** Get the action
     * @access public
     * @return string
     */
    public function getAction()
    {
        $this->_action = $this->getRequest()->getActionName();
        return $this->_action;
    }

    /** Get the controller
     * @access public
     * @return string
     */
    public function getController()
    {
        $this->_controller = $this->getRequest()->getControllerName();
        return $this->_controller;
    }

    /** Get the time span
     * @access public
     * @return string
     */
    public function getTimeSpan()
    {
        $this->_timeSpan = $this->getRequest()->getParam('timespan');
        return $this->_timeSpan;
    }

    /** Set the timespan
     * @access public
     * @param string $timeSpan
     * @return \Pas_View_Helper_TimeSpansGa
     */
    public function setTimeSpan($timeSpan)
    {
        $this->_timeSpan = $timeSpan;
        return $this;
    }

    /** Get the available timespans
     * @access public
     * @return array
     */
    public function getTimespans()
    {
        return $this->_timespans;
    }

    /** Get the request
     * @access public
     * @return \Zend_Controller_Front
     */
    public function getRequest()
    {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_request;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_TimeSpansGa
     */
    public function timeSpansGa()
    {
        return $this;
    }

    /** Create urls for rendering as to string
     * @access public
     * @return string
     */
    public function _createUrls()
    {
        $html = '<ul class="nav nav-pills">';
        foreach ($this->getTimespans() as $k => $v) {
            $html .= '<li class="';
            if ($this->_timeSpan === $v) {
                $html .= 'active';
            } elseif (is_null($this->getTimeSpan()) && $v === 'thisweek') {
                $html .= 'active';
            }
            $html .= '"><a href="';
            $html .= $this->view->url(array(
                    'module' => $this->getModule(),
                    'controller' => $this->getController(),
                    'action' => $this->getAction(),
                    'timespan' => $v),
                'default', false);
            $html .= '">' . ucfirst($k);
            $html .= '</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->_createUrls();
    }
}
