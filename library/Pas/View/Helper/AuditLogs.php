<?php
/**
 * A view helper for rendering audit logs on a finds record
 * 
 * This helper uses data from the finds, findspots and coins models and then
 * redisplays this as a list of audited actions that are clickable and this 
 * instatiates a modal window with the changes within it.
 * 
 * To use this, follow the example below:
 * <code>
 * <?php
 * echo $this->auditLogs()->setID(1);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @license http://URL GNU
 * @uses Pas_User_Details
 * 
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_AuditLogs extends Zend_View_Helper_Abstract
{
    /** The roles allowed to view audit logs
     * @access protected
     * @var array
     */
    protected $_allowed = array('flos', 'hero', 'treasure', 'fa', 'admin');

    /** The user's role
     * @access protected
     * @var string
     */
    protected $_role = 'public';
    
    /** ID number to query
     * @access protected
     * @var int
     */
    protected $_id = 1;
    
    /** Get the allowed array
     * @access public
     * @return array
     */
    public function getAllowed() {
        return $this->_allowed;
    }

    /** Get the role of the user
     * @access public
     * @return string
     */
    public function getRole() {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
        $this->_role = $person->role;
        } 
        return $this->_role;
    }

    /** get the id of the find
     * @access public
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the id of the find to query
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_AuditLogs
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_AuditLogs
     */
    public function auditLogs() {
        return $this;
    }
    
    /** the to string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml( $this->getId() );
    }

    /** Build the function for returning html
     * @access public
     * @param int $id
     * @return string
     */
    public function buildHtml($id) {
        $html = '';
        if (!is_null($this->getRole() && is_int($id))) {
        $html .= '<ul id="tab" class="nav nav-tabs">';
        $html .= '<li class="active"><a href="#findAudit" data-toggle="tab">Finds audit</a></li>';
        $html .= '<li><a href="#fspot" data-toggle="tab">Findspot audit</a></li>';
        $html .= '<li><a href="#coinAudit" data-toggle="tab">Numismatic audit</a></li>';
        $html .= '</ul>';
        $html .= '<div id="myTabContent" class="tab-content">';
        $html .= '<div class="tab-pane fade in active" id="findAudit">';
        $html .= $this->view->auditDisplay()->setId($id)->setTableName('finds');
        $html .= '</div>';
        $html .= '<div class="tab-pane fade" id="fspot">';
        $html .= $this->view->auditDisplay()->setId($id)->setTableName('findspots');
        $html .= '</div>';
        $html .= '<div class="tab-pane fade" id="coinAudit">';
        $html .= $this->view->auditDisplay()->setId($id)->setTableName('coins');
        $html .= '</div></div>';
        }
        return $html;
    }
}
