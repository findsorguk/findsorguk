<?php
class Issues extends Pas_Db_Table_Abstract {

	protected $_name = 'issues';
	protected $_primary = 'id';

	/** Get completeness details by id
	* @param integer $id
	* @return array
	* @todo change to fetchrow in future?
	* @todo add caching
	*/
	public function getAllIssues() {
	
	}
	
	public function getIssue($id){
		
	}
	
	public function addIssue($data){
		
	}
	
	public function editIssue($data, $where){
		
	}
	
}