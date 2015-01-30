<?php
/** View helper for displaying the number of changes for findspot records from audit table
 * @category Pas
 * @package Pas_View_Helper
 * @uses Pas_View_Helper_TimeAgoInWords
 * @uses Zend_View_Helper_Url
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright DEJ PETT <dpett@britishmuseum.org>
 * @author Daniel Pett
 * @version 1
 * @since September 29 2011
 */
class Pas_View_Helper_ChangesFindSpot extends Zend_View_Helper_Abstract
{

    /** The response if no changes made
     *
     */
    const NOTHING = '<p>No changes made so far.</p>';

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

    /** The allowed roles
     * @access public
     * @return array
     */
    public function getAllowed() {
        return $this->_allowed;
    }

    /** Set the id to query
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_ChangesFindSpot
     */
    public function setId( int $id) {
        $this->_id = $id;
        return $this;
    }

    /** Get the user's role
     * @access protected
     * @return string
     */
    protected function _getRole(){
        $role = new Pas_User_Details();
        return $role->getPerson()->role;
    }

    /** The allowed roles
     * @access protected
     * @var array
     */
    protected $_allowed = array(
        'treasure', 'flos', 'fa',
        'admin', 'hoard'
    );

    /** Get the data to render
     * @access private
     * @return string
     */
    private function _getData() {
        $html = '';
        if(in_array($this->_getRole(), $this->getAllowed())){
            $audit = new FindSpotsAuditData();
            $auditdata = $audit->getChanges($thos->getId());
            if($auditdata) {
                $html .= '<h5>Find spot data audit</h5>';
                $html .='<ul id="related">';
                $html .= $this->parseArray( $auditdata );
                $html .= '</ul>';
            return $html;
            } else {
                $html .= self::NOTHING;
            }
        return $html;
        }
    }

    /** Build the html
     * @access public
     * @param array $a
     * @return string
     */
    public function buildHtml( array $a) {
        $html = '';
	$html .= '<li><a class="overlay" href="';
	$html .= $this->view->url(array(
		'module' => 'database',
		'controller' => 'ajax',
		'action' => 'fsaudit',
		'id' => $a['editID']),
	null,true);
	$html .= '" title="View all changes on this date">';
	$html .= $this->view->timeAgoInWords($a['created']);
	$html .= '</a> ';
	$html .= $a['fullname'];
	$html .= ' edited this record.</li>';
	return $html;
    }

    /** the methid
     * @access public
     * @return \Pas_View_Helper_ChangesFindSpot
     */
    public function ChangesFindSpot() {
        return $this;
    }

    /** To string!
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->_getData();
    }

    /** Parse the array
     * @access public
     * @param array $array
     * @return string
     */
    public function parseArray( array $array ) {
        $html = '';
        foreach($array as $a) {
            $html .= $this->buildHtml($a);
        }
        return $html;
    }
}