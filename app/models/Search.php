<?php
/** Data model for accessing data for array based searching
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		this is to be replaced by SOLR and sucks the big dong
*/
class Search extends Pas_Db_Table_Abstract {

	protected $_name = 'finds';

	protected $_primary = 'id';

	protected $_higherlevel = array('admin','flos','fa');

	protected $_research = array('hero','research');

	protected $_restricted = array('public','member');

	protected $_edittest = array('flos','member');



	/** Get user's role for checking permissions
	* @return string $role
	*/
	public function getRole() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$role = $user->role;
	return $role;
	} else {
	$role = 'public';
	return $role;
	}
	}

	/** Get user's institution
	* @return string $institution
	*/
	protected function getInstitution() {
	if($this->_auth->hasIdentity()) {
	$user = $this->_auth->getIdentity();
	$institution = $user->institution;
	return $institution;
	}
	}


}