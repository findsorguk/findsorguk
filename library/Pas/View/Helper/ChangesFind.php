<?php
/** View helper for displaying the number of changes for find records from audit table
 * @category Pas
 * @package Pas_View_Helper
 * @uses Pas_View_Helper_TimeAgoInWords
 * @uses Zend_View_Helper_Url Url helper
 * @license GNU
 * @copyright DEJ PETT
 * @author Daniel Pett
 * @version 1
 * @since September 29 2011
 */
class Pas_View_Helper_ChangesFind extends Zend_View_Helper_Abstract
{

    /** The ID to query
     *
     * @var int
     */
    protected $_id;

    /** Get the id to query
     * @access public
     * @return type
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the id to query
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_ChangesFind
     */
    public function setId( int $id) {
        $this->_id = $id;
        return $this;
    }

    /** Constant to return
     *
     */
    const NOTHING = '<p>No changes made so far.</p>';

    /** Get the user's role
     * @access protected
     * @return type
     */
    protected function _getRole(){
        $role = new Pas_User_Details();
        return $role->getPerson()->role;
    }

    /** The array of allowed roles to view the changes
     * @access protected
     * @var type
     */
    protected $_allowed = array('treasure', 'flos', 'fa','admin', 'hero');


    /** Build the html from data array
     *  @param array $a
     *  @return string $html
     *  @access public
     */
    public function buildHtml($a) {
        $html = '';
	$html .= '<li><a class="overlay" href="';
	$html .= $this->view->url(array(
            'module' => 'database',
            'controller' => 'ajax',
            'action' => 'audit',
            'id' => $a['editID']
                ),NULL,true);
	$html .= '" title="View all changes on this date">';
	$html .= $this->view->timeAgoInWords($a['created']);
	$html .= '</a> ';
	$html .= $a['fullname'];
	$html .= ' edited this record.</li>';
	return $html;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ChangesFind
     */
    public function changesFind() {
        return $this;
    }

    /** The magic to string method
     * @access public
     * @return type
     */
    public function __toString() {
        return $this->getData();
    }

    /** Get the data to redisplay
     * @access public
     * @return type
     */
    public function getData() {
        $html = '';
        if(in_array($this->_getRole(), $this->_allowed)){
            $audit = new FindsAudit();
            $auditdata = $audit->getChanges($this->getId());
            if($auditdata) {
                $html .='<h5>Finds data audit</h5>';
                $html .='<ul id="related">';
                $html .= $this->parseArray( $auditdata );
                $html .='</ul>';
                } else {
                    $html .= self::NOTHING;

                }
            }
        return $html;
    }

    /** Parse the array
     * @access public
     * @param array $array
     * @return typ
     */
    public function parseArray( array $array ) {
        $html = '';
        foreach($array as $a) {
                    $html .= $this->buildHtml($a);
                }
        return $html;
    }
}