<?php
/** A view helper class to display the findspot edit delete links
* @todo Perhaps change to an asserts acl check
* @todo DRY the access groups to another class, this is used too often
* @category Pas
* @package  Pas_View_Helper
* @subpackage Abstract
* @since September 27 2011
* @author Daniel Pett
* @version 1
* @uses Zend_View_Helper_Url
*/
class Pas_View_Helper_FindSpotEditDeleteLink
	extends Zend_View_Helper_Abstract {

	/** Array where no access is granted
	 * @var array $_noaccess
	 */
	protected $_noaccess = array('public');

	/** Array of restricted access
	 * @var array $_restricted
	 */
	protected $_restricted = array('member','research','hero');

	/** Array of users roles with recording privileges
	 * @var array $_recorders
	 */
	protected $_recorders = array('flos');

	/** Array of higher level users
	 * @var array $_higherLevel
	 */
	protected $_higherLevel = array('admin','fa','treasure', 'flos');


	/** Message for missing group exception
	 * @var string $_missingGroup
	 */
	protected $_missingGroup = 'User is not assigned to a group';

	/** Message for access rights exception
	 * @var string $_message
	 */
	protected $_message = 'You are not allowed edit rights to this record';

	/** Construct the auth object
	 */
	public function __construct() {
    }
    /** Get the user's role
     */
	public function getPerson() {
	$user = new Pas_User_Details();
	return $user->getPerson();
	}
	
	/** Check for access by userID
	 * @param int $createdBy
	 */
	public function checkAccessbyUserID($createdBy) {
	if(!in_array($this->getPerson()->role,$this->_restricted)) {
	return true;
	} else if(in_array($this->getPerson()->role,$this->_restricted)) {
	if($createdBy == $this->getPerson()->id) {
	return true;
	}
	} else {
	return false;
	}
	}
	/** Check for access by institution
	 *
	 * @param string $findspotID
	 */
	public function checkAccessbyInstitution($institution){
	if($this->getPerson()->institution == $institution) {
	return true;
	} else if((in_array($this->getPerson()->role,$this->_recorders) && ($institution == 'PUBLIC'))) {
	return true;
	}
	}


	/** Check and display links for edit
	 *
	 * @param string $findspotID
	 * @param int $ID
	 * @param int $createdBy
	 */
	public function FindSpotEditDeleteLink($findspotID, $ID, $createdBy, $institution = 'PUBLIC') {
	$byID = $this->checkAccessbyUserID($createdBy);
	$instID = $this->checkAccessbyInstitution($findspotID);

	if(in_array($this->getPerson()->role,$this->_restricted)) {
	if(($byID == TRUE && $instID== TRUE) || ($byID == TRUE && $instID == FALSE)) {
	return $this->buildHtml($ID);
	}
	} else if(in_array($this->getPerson()->role,$this->_higherLevel)) {
	return $this->buildHtml($ID);
	} else if(in_array($this->getPerson()->role,$this->_recorders)) {
	if(($instID == TRUE && $byID == FALSE) || ($byID == true && $instID == true) ||
	($byID == false && $instID == true)) {
	return $this->buildHtml($ID);
	}
	}
	}

	/** Build the HTML links
	 *
	 * @param int $ID
	 * @return string $html
	 */
	public function buildHtml($ID) {
	$editClass = 'btn btn-small btn-warning';
	$deleteClass = 'btn btn-small btn-danger';
	$editurl = $this->view->url(array('module' => 'database','controller' => 'findspots','action' => 'edit',
	'id' => $ID),null,true);
	$deleteurl = $this->view->url(array('module' => 'database','controller' => 'findspots','action' => 'delete',
	'id' => $ID),null,true);
	$html = '<p><a class="' .$editClass . '" href="' . $editurl
	. '" title="Edit spatial data for this record">Edit findspot <i class="icon-edit icon-white"></i></a> <a class="' . $deleteClass . '" href="' . $deleteurl
	. '" title="Delete spatial data">Delete findspot <i class="icon-trash icon-white"></i></a></p>';
	return $html;
	}

}
