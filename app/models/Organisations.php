<?php
/** Model for pulling organisational data from database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/
class Organisations extends Pas_Db_Table_Abstract {

	protected $_name = 'organisations';

	protected $_primary = 'id';
	
	/** Get a list of organisations as key value pairs
	* @return array
	*/
	public function getOrgs() {
		$select = $this->select()
			->from($this->_name, array('secuid', 'name'))
			->order('name');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }
    
    /** Search for organisation name via query string
	* @param string $q organisation name starts with 
	* @return array
	* @todo do something better!
	*/
	public function getOrgNames($q) {
		$select = $this->select()
			->from($this->_name, array('id' => 'secuid', 'term' => 'name'))
			->where('name LIKE ?', (string)'%' . $q . '%')
			->order('name')
			->limit(20);
	        $options = $this->getAdapter()->fetchAll($select);
	    return $options;
    }
	
    /** Get an organisation's details by id number
	* @param integer $id
	* @return array
	*/
	public function getOrgDetails($id){
		$orgs = $this->getAdapter();
		$select = $orgs->select()
			->from($this->_name)
			->joinLeft('countries','organisations.country = countries.iso', 
			array('abode' => 'printable_name'))
			->joinLeft('people','organisations.contactpersonID = people.secuid', 
			array('fn' => 'fullname', 'tit' => 'title','i' => 'id'))
			->where('organisations.id = ?', (int)$id);
	  	return $orgs->fetchAll($select);
	}

	/** Get a list of members for organisation
	* @param integer $id 
	* @return array
	*/
	public function getMembers($id) {
		$orgs = $this->getAdapter();
		$select = $orgs->select()
			->from($this->_name)
			->joinLeft('people','organisations.secuid = people.organisationID', 
			array('fnmembers' => 'fullname','i' => 'id'))
			->where('fullname IS NOT NULL AND organisations.id = ?', (int)$id)
			->order('fnmembers ASC');
		return $orgs->fetchAll($select);
	}
	
	/** Get a list of organisations paginated
	* @param integer $params['page'] page number
	* @param string $params['county'] county name
	* @param string $params['organisation'] organisation name
	* @param string $params['contactpersonID'] contact person name
	* @return array
	* @todo this function sucjs the big one, rewrite it properly doofus
	*/
	public function getOrganisations($params) {
		$orgs = $this->getAdapter();
		$select = $orgs->select()
			->from($this->_name)
			->joinLeft('people','organisations.contactpersonID = people.secuid', 
			array('fullname','i' => 'id'))
			->order(array('name'));
		if(isset($params['county']) && ($params['county'] != ""))  {
		$county = strip_tags($params['county']);
		$select->where($this->_name . '.county = ?', (string)$county);
		}
		if(isset($params['organisation']) && ($params['organisation'] != ""))  {
		$org = strip_tags($params['organisation']);
		$select->where('name = ?', (string)$org);
		}
		if(isset($params['contactpersonID']) && ($params['contactpersonID'] != "")) {
		$con = strip_tags($params['contactpersonID']);
		$select->where('contactpersonID = ?', (string)$con);
		}
		$paginator = Zend_Paginator::factory($select);
		if(isset($params['page']) && ($params['page'] != "")) {
		$paginator->setCurrentPageNumber((int)$params['page']); 
		}
		$paginator->setItemCountPerPage(50) 
	          ->setPageRange(10); 
		return $paginator;
	}
}
