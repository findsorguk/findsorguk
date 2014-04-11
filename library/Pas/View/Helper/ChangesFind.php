<?php
/** View helper for displaying the number of changes for find records from audit table
 * @category Pas
 * @package Pas_View_Helper
 * @uses Pas_View_Helper_TimeAgoInWords
 * @license GNU
 * @copyright DEJ PETT
 * @author Daniel Pett
 * @version 1
 * @since September 29 2011
 */
class Pas_View_Helper_ChangesFind
	extends Zend_View_Helper_Abstract {

	const NOTHING = '<p>No changes made so far.</p>';
	
	protected function _getRole(){
	$role = new Pas_User_Details();
	return $role->getPerson()->role;
	}

	protected $_allowed = array('treasure', 'flos', 'fa','admin');

	/** Build the html from data array
	* @param array $a
	* @return string $html
	*/
	public function buildHtml($a) {
	$html = '';
	$html .= '<li><a class="overlay" href="';
	$html .= $this->view->url(array('module' => 'database', 'controller' => 'ajax', 'action' => 'audit',
	'id' => $a['editID']),NULL,true);
	$html .= '" title="View all changes on this date">';
	$html .= $this->view->timeagoinwords($a['created']);
	$html .= '</a> ';
	$html .= $a['fullname'];
	$html .= ' edited this record.</li>';
	return $html;
	}

	/** Query for data and display
	* @param int $id
	* @return string $html
	*/
	public function ChangesFind($id) {
	if(in_array($this->_getRole(), $this->_allowed)){
	$audit = new FindsAudit();
	$auditdata = $audit->getChanges($id);
	if($auditdata) {
	$html ='<h5>Finds data audit</h5>';
	$html .='<ul id="related">';
	foreach($auditdata as $a) {
	$html .= $this->buildHtml($a);
	}
	$html .='</ul>';
	return $html;
	} else {
		return self::NOTHING;
	}
	}

}

}