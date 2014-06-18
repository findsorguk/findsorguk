<?php
/**
 * A view helper to build the audit logs html string
 * @author Daniel Pett <dpett @ britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @since 17/5/2014
 * @license http://URL GNU
 * @todo Does this need to be returned based on roles?
 */
class Pas_View_Helper_AuditLogs extends Zend_View_Helper_Abstract
{

    /** The id to query
     * @access protected
     * @var int
     */
    protected $_id;

    /** Get the id to query
     * @access public
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the id to query
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_AuditLogs
     */
    public function setId( int $id) {
        $this->_id = $id;
        return $this;
    }

    /** The audit log function
     * @access public
     * @return \Pas_View_Helper_AuditLogs
     */
    public function auditLogs() {
	return $this;
    }

    /** Get data to return
     * @access public
     * @return string
     */
    public function _getData() {
        return $this->buildHtml($this->getId());
    }

    /** Magic method to string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->_getData();
    }

    /** Build the html to return
     * @access public
     * @param type $id
     * @return string
     */
    public function buildHtml( int $id){
        $html = '';
        $html .= '<ul id="tab" class="nav nav-tabs">';
        $html .= '<li class="active"><a href="#findAudit" data-toggle="tab">Finds audit</a></li>';
        $html .= '<li><a href="#fspot" data-toggle="tab">Findspot audit</a></li>';
        $html .= '<li><a href="#coinAudit" data-toggle="tab">Numismatic audit</a></li>';
        $html .= '</ul>';
        $html .= '<div id="myTabContent" class="tab-content">';
        $html .= '<div class="tab-pane fade in active" id="findAudit">';
        $html .= $this->view->changesFind()->setId($id);
        $html .= '</div>';
        $html .= '<div class="tab-pane fade" id="fspot">';
        $html .= $this->view->changesFindSpot()->setId($id);
        $html .= '</div>';
        $html .= '<div class="tab-pane fade" id="coinAudit">';
        $html .= $this->view->changesCoins()->setId($id);
        $html .= '</div></div>';
        return $html;
	}
}

