<?php
/** Data model for accessing and manipulating Reece period data
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 October 2010, 17:12:34
* @todo 		add edit and delete functions
* @todo 		add caching
*/

class Reeces extends Pas_Db_Table_Abstract {

	protected $_name = 'reeceperiods';

	protected $_primary = 'id';


	/** Retrieve key value pairs for Reece period dropdowns
	* @return array
	* @todo add caching
	*/
	public function getOptions() {
			$select = $this->select()
					->from($this->_name, array('id', 'period_name'))
					->order($this->_primary)
					->where('valid =?', (int)1);
			$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
	
    /** Retrieve key value pairs for Reece period dropdowns greater than period 15
	* @return array
	* @todo add caching and rename function
	*/
	public function getRevTypes() {
			$select = $this->select()
						->from($this->_name, array('id', 'period_name'))
						->where('id >= ?', (int)15)
						->order($this->_primary);
			$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }
	
    /** Retrieve counts of coins per reece period, expensive
	* @return array
	* @todo add caching and rename function
	*/
    public function getReeceTotals() {
 		if (!$data = $this->_cache->load('reecetotals')) {	
			$rulers = $this->getAdapter();
			$select = $rulers->select()
						->from('finds', array( 'quantity' => 'SUM(quantity)' ))
						->joinLeft('coins','coins.findID = finds.secuid',array())
					    ->joinLeft($this->_name,'coins.reeceID = ' . $this->_name.'.id',
					    array('id', 'period_name', 'description'))
			            ->order($this->_name . '.id ASC')
						->where('coins.reeceID IS NOT NULL')
						->group($this->_name . '.id');
			$data =  $rulers->fetchAll($select);
			$this->_cache->save($data, 'reecetotals');
		} 
	return $data;
	}
	
	/** Retrieve rulers for a specific reece period, one to many relationship
	* @param integer $ruler ruler id number 
	* @return array
	* @todo add caching and rename function
	*/
	public function getRulerReece($ruler) {
			$reeces = $this->getAdapter();
			$select = $reeces->select()
						->from($this->_name, array('id','term' => 'CONCAT(period_name," - ",description," ","(",date_range,")")'))
						->joinLeft('reeceperiods_rulers','reeceperiods.id = reeceperiods_rulers.reeceperiod_id',array())
						->joinLeft('rulers','rulers.id = reeceperiods_rulers.ruler_id',array())
						->where('rulers.id = ?', (int)$ruler)
						->order('period_name ASC');
    return $reeces->fetchAll($select);
    }	
    
    /** Get unassigned reece periods (greater than 14)
	* @return array
	* @todo add caching and rename function
	*/
	public function getReeceUnassigned(){
			$reeces2 = $this->getAdapter();
			$select = $reeces2->select()
						->from($this->_name, array('id','term' => 'CONCAT(period_name," - ",description," ","(",date_range,")")'))
						->where('reeceperiods.id > ?', (int)14)			
						->order('period_name ASC');
	return $reeces2->fetchAll($select);
    }	
	
    /** Retrieve all valid reece periods as key value pairs for dropdown on forms or ajax
	* @return array
	* @todo add caching and rename function
	*/
	public function getReeces() {
			$reeces2 = $this->getAdapter();
			$select = $reeces2->select()
						->from($this->_name, array('id','term' => 'CONCAT(period_name," - ",description," ","(",date_range,")")'))
						->where('valid = ?',(int)1)
						->order('id ASC');
	return $reeces2->fetchPairs($select);
    }

    /** Retrieve all valid reece periods for admin interface
	* @return array
	* @todo add caching and rename function
	*/
	public function getReecesAdmin() {
			$reeces = $this->getAdapter();
			$select = $reeces->select()
						->from($this->_name)
						->joinLeft('users',$this->_name . '.createdBy = users.id', array('fullname'))
						->joinLeft('users',$this->_name . '.updatedBy = users_2.id', array('fn' => 'fullname'))
			->order('id ASC');
        return $reeces->fetchAll($select);
    }	

    /** Retrieve details for specific reece period
    * @param integer $id period id number
	* @return array
	* @todo add caching and rename function
	*/
	public function getReecePeriodDetail($id){
			$reeces = $this->getAdapter();
			$select = $reeces->select()
						->from($this->_name,array('id','period_name','description','date_range','created','updated'))
						->joinLeft('users',$this->_name . '.createdBy = users.id', array('createdBy' => 'fullname'))
						->joinLeft('users',$this->_name . '.updatedBy = users_2.id', array('updatedBy' => 'fullname'))
						->where($this->_name . '.id = ?',(int)$id);
	return $reeces->fetchAll($select);
	}

	/** Retrieve details for periods for site map
	* @return array
	* @todo add caching and rename function
	*/
	public function getSiteMap(){
	if (!$data = $this->_cache->load('reecescached')) {	
			$rulers = $this->getAdapter();
			$select = $rulers->select()
						->from($this->_name, array('id', 'period_name','updated'));
			$data = $rulers->fetchAll($select);
			$this->_cache->save($data, 'reecescached');
		} 
	return $data;
	}
}