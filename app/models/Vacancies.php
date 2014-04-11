<?php
/**  Data model for accessing vacancy data in database
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		caching
*/
class Vacancies extends Pas_Db_Table_Abstract {

	protected $_name = 'vacancies';

	protected $_primary = 'id';

	/** Get current vacancies
	* @return array
	*/
	public function getCurrent() {
	$select = $this->select()
    	->from($this->_name)
		->order('id DESC');
	$vacs = $this->getAdapter()->fetchAll($select);
	return $vacs;
	}

	/** Get current vacancies live
	* @param integer $page page to retrieve
	* @return array
	*/
	public function getLiveJobs($page){
	$livejobs = $this->getAdapter();
	$select = $livejobs->select()
		->from($this->_name)
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = vacancies.regionID',array('staffregions' => 'description'))
		->where('live <= ?', Zend_Date::now()->toString('yyyy-MM-dd'))
		->where('expire >= ?', Zend_Date::now()->toString('yyyy-MM-dd'))
		->order('id');
	$paginator = Zend_Paginator::factory($select);
	Zend_Paginator::setCache($this->_cache);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Get current vacancies live limited
	* @param integer $limit limit to return
	* @return array
	*/
	public function getJobs($limit) {
	$livejobs = $this->getAdapter();
	$select = $livejobs->select()
		->from($this->_name)
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = vacancies.regionID',array('staffregions' => 'description'))
		->where('status = ?', (int)'2')
		->order('id DESC')
		->limit((int)$limit);
    return $livejobs->fetchAll($select);
	}

	/** Get archived vacancies live limited
	* @param integer $page page to return
	* @return array
	*/

	public function getArchiveJobs($page){
	$archivejobs = $this->getAdapter();
	$select = $archivejobs->select()
		->from($this->_name)
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = vacancies.regionID',array('staffregions' => 'description'))
		->where('live <= CURDATE()')
		->where('expire <= CURDATE()')
		->where('status = ?', (int)'2')
		->order('id');
	$data = $archivejobs->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	Zend_Paginator::setCache($this->_cache);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Get job details by ID
	* @param integer $id job id
	* @return array
	*/
	public function getJobDetails($id){
	$details = $this->getAdapter();
	$select = $details->select()
		->from($this->_name)
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = vacancies.regionID',
		array('staffregions' => 'description'))
		->where($this->_name . '.id = ?', (int)$id);
    $data = $details->fetchAll($select);
    return $data;
	}

	/** Get vacancies for admin
	* @param integer $page page to return
	* @return array
	*/
	public function getJobsAdmin($page){
	$livejobs = $this->getAdapter();
	$select = $livejobs->select()
		->from($this->_name)
		->joinLeft(array('locality' => 'staffregions'),'locality.ID = vacancies.regionID',array('staffregions' => 'description'))
		->joinLeft('users','users.id = '.$this->_name.'.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = '.$this->_name.'.updatedBy',array('fn' => 'fullname'))
		->order('id DESC');
	$data = $livejobs->fetchAll($select);
	$paginator = Zend_Paginator::factory($select);
	Zend_Paginator::setCache($this->_cache);
	$paginator->setItemCountPerPage(30)
	          ->setPageRange(10);
	if(isset($page) && ($page != "")) {
    $paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

}
