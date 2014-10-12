<?php
/** View helper for displaying the number of changes for record tables from the
 * audit table.
 *
 * There are 3 tables being audited on each record: Coin, Finds and Findspots.
 * Each of these can be queried to see what has changed and by whom.
 *
 * To use this helper.
 * <code>
 * <?php
 * echo $this->auditDisplay()->setTableName('finds')->setId(1);
 * ?>
 * </code>
 *
 * @category Pas
 * @package Pas_View_Helper
 * @uses Pas_View_Helper_TimeAgoInWords
 * @uses Zend_View_Helper_Url
 * @license GNU
 * @copyright 2014 Daniel Pett
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 2
 * @since September 29 2011
 */
class Pas_View_Helper_AuditDisplay extends Zend_View_Helper_Abstract {

    /** If no changes found
     * @access protected
     * @var string
     */
    protected $_nothing = '<p>No changes made so far.</p>';

    /** The id to query
     * @access protected
     * @var int
     */
    protected $_id;

    /** The role default
     * @access protected
     * @var string
     */
    protected $_role = 'public';

    /** The allowed array
     * @access protected
     * @var array
     */
    protected $_allowed = array(
        'treasure', 'flos', 'fa',
        'admin', 'hoard'
    );

    /** The default table name
     * @access protected
     * @var string
     */
    protected $_tableName = 'finds';

    /** The array of tables to query
     * @access protected
     * @var array
     */
    protected $_tableNames = array('finds', 'findspots', 'coins', 'hoards');

    /** Get the table name
     * @access public
     * @return string
     */
    public function getTableName() {
        return ucfirst($this->_tableName);
    }

    /** Set the table name
     * @access public
     * @param string $tableName
     * @return \Pas_View_Helper_AuditDisplay
     * @throws Pas_Exception_Param
     */
    public function setTableName($tableName) {
        if(in_array( $tableName, $this->_tableNames )){
            $this->_tableName = $tableName;
        } else {
            throw new Pas_Exception_Param('Table not available', 500);
        }
        return $this;
    }

    /** If nothing found, default message
     * @access public
     * @return type
     */
    public function getNothing() {
        return $this->_nothing;
    }

    /** Get the ID number to query
     * @access publc
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Get the role
     * @access public
     * @return type
     */
    public function getRole() {
        $person = new Pas_User_Details();
        $this->_role = $person->getRole();
        return $this->_role;
    }

    /** Get the allowed array
     * @access public
     * @return type
     */
    public function getAllowed() {
        return $this->_allowed;
    }

    /** Change the nothing found message
     * @access public
     * @param string $nothing
     * @return \Pas_View_Helper_AuditDisplay
     */
    public function setNothing($nothing) {
        $this->_nothing = $nothing;
        return $this;
    }

    /** Set the ID number
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_AuditDisplay
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_AuditDisplay
     */
    public function auditDisplay() {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getData( $this->getId() );
    }

    /** Get the data to return
     * @access public
     * @param int $id
     * @return string
     */
    public function getData( $id ) {
        $id = (int)$id;
        if (in_array($this->getRole(), $this->getAllowed())) {
            $model = $this->getTableName() . 'Audit';
            $audit = new $model();
            $auditData = $audit->getChanges($id);
        } else {
            $auditData = array();
        }
        return $this->buildHtml( $auditData );
    }

    /** Build the Url to query
     * @access public
     * @param string $editID
     * @return string
     */
    public function buildUrl( $editID ) {
        $params = array(
            'module' => 'database',
            'controller' => 'ajax',
            'action' => $this->getTableName() . 'audit',
            'id' => $editID
            );
        return $params;
    }

    /** Build the html from data array
    * @param array $auditData
    * @return string $html
    */
    public function buildHtml(array $auditData ) {
        $html = '';
        $html .= '<h4 class="lead">';
        $html .= $this->getTableName();
        $html .= ' data audit</h4>';
        if(is_array($auditData) && sizeof($auditData) > 0){
            $html .= '<ul>';
            foreach($auditData as $audit) {
                $html .= '<li><a class="overlay" href="';
                $html .= $this->view->url($this->buildUrl($audit['editID']),null,true);
                $html .= '" title="View all changes on this date">';
                $html .= $this->view->timeAgoInWords($audit['created']);
                $html .= '</a> ';
                $html .= $audit['fullname'];
                $html .= ' edited this record.</li>';
            }
            $html .= '</ul>';
        } else {
            $html .= $this->getNothing();
        }
        return $html;
    }
}
