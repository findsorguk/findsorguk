<?php
/** A view helper for rendering links for checking records
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @author Daniel Pett
 * @version 1
 * @since 20/1/2012
 * @copyright DEJ PETT
 */
class Pas_View_Helper_NotifyFloPublic
	 extends Zend_View_Helper_Abstract {
	protected $_allowed = array( 'member', 'flos', 'treasure', 'admin', 'fa' );

	/* The user object
	 *
	 */
	protected $_user;

	/** Set the user up
	 *
	 */
	public function __construct() {
    $user = new Pas_User_Details();
    if($user){
    $this->_user = $user->getPerson();	
    } else {
    	throw new Pas_Exception_BadJuJu('No user credentials found');
    }
	}

	/** Check if able to render
	 *
	 * @param string $institution
	 * @param int $id
	 * @param int $workflow
	 */
	public function notifyFloPublic($institution, $id, $workflow){
		if(($workflow < 3) && ($institution === 'PUBLIC')
			&& in_array($this->_user->role, $this->_allowed)){
			return $this->_buildHtml($id);
		} else {
			return false;
		}
	}

	/** Render the html
	 *
	 * @param int $id
	 */
	private function _buildHtml($id) {		
		$html = '<div>';
		$html .= '<p><a class="btn btn-large btn-info" href ="';
		$html .= $this->view->serverUrl() . '/database/artefacts/notifyflo/id/' . $id;
		$html .= '" title="Get this published">Get this record checked or published by your flo</a></p></div>';
		return $html;
	}

}