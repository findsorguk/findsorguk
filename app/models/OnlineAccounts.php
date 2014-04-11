<?php 
/** Model for pulling person's online accounts
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
*/

class OnlineAccounts extends Pas_Db_Table_Abstract {

	protected $_name = 'userOnlineAccounts';

	protected $_primaryKey = 'id';

	
	/** Get a list of online personas by userid for staff members
	* @param integer $id staff member id number 
	* @return array
	*/
	public function getAccounts($id){
	$accs = $this->getAdapter();
	$select = $accs->select()
		->from($this->_name,array('account','accountName'))
		->joinLeft('webServices',$this->_name . '.accountName = webServices.service', 
		array('serviceUrl'))
		->joinLeft('staff',$this->_name . '.userID = staff.dbaseID', array())
		->where('staff.id = ?', (int)$id)
		->where($this->_name . '.public = 1');
	$data =  $accs->fetchAll($select);
	return $data;
	}
	
	/** Get slideshare account name for api call
	* @param integer $id staff member id number 
	* @return array
	* @todo this could be fetchrow!
	*/
	public function getSlideshare($id) {
	if (!$data = $this->_cache->load('slideshare' . $id)) {
	$accs = $this->getAdapter();
	$select = $accs->select()
		->from($this->_name,array('account'))
		->joinLeft('webServices',$this->_name . '.accountName = webServices.service', array())
		->where('userID = ?', (int)$id)
		->where('accountName = ?','Slideshare')
		->where($this->_name.'.public = 1')
		->limit(1);
	$data = $accs->fetchAll($select);
	$this->_cache->save($data, 'slideshare' . $id);
	}
    return $data;
	}

	/** List all subscribed third party web services for html
	* @param integer $userID
	* @return array
	* @todo this could be fetchrow!
	*/
	public function getAllAccounts($userID) {
	$accs = $this->getAdapter();
	$select = $accs->select()
		->from($this->_name, array('id','account', 'accountName', 'public'))
		->joinLeft('webServices', $this->_name . '.accountName = webServices.service', 
		array('serviceUrl'))
		->where($this->_name . '.userID = ?',$userID);
	$data =  $accs->fetchAll($select);
    return $data;
	}
}