<?php
/** A model for manipulating error report data
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add edit and delete functions
*/
class ErrorReports extends Pas_Db_Table_Abstract {
	
	protected $_name = 'errorreports';
	
	protected $_primary = 'id';
	
	
	/** insert error report data
	* @param array $formData 
	* @return boolean
	*/
	public function addReport($formData) {
        return $this->insert($formData);
    }
    
    /** Retrieve a count of the error reports submitted so far
	* @return Array
	*/
	public function getCount(){
		$messages = $this->getAdapter();
		$select = $messages->select()
                       ->from($this->_name,array('total' => 'COUNT(id)'));
        return $messages->fetchAll($select);	
	}

	/** Retrieve all submitted error messages so far
	* @param array $params
	* @return array $paginator
	*/
	public function getMessages($params){
	$messages = $this->getAdapter();
	$select = $messages->select()
    	               ->from($this->_name)
    	               ->joinLeft('finds','finds.id = comment_findID', 
    	               array('broadperiod','objecttype','old_findID'))
    	               ->order($this->_name . '.id DESC');
    $data = $messages->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	$paginator->setCache($this->_cache);
	$paginator->setItemCountPerPage(20) 
			  ->setPageRange(10) ;
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']); 
	}
	return $paginator;
	}
}