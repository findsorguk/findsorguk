<?php
/** A model for manipulating Iron Age geography
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching
*/
class Geography extends Pas_Db_Table_Abstract {
	
	protected $_name = 'geographyironage';
	
	protected $_primary = 'id';

	/** Get Iron Age geographical region by term id
	* @param integer $term 
	* @return array
	* @todo add caching
	*/
	public function getIronAgeGeography($term){
		$regions = $this->getAdapter();
		$select = $regions->select()
			->from($this->_name, array('id', 'term' => 'CONCAT(region,"  ",IFNULL(area,""),"  ",IFNULL(tribe,""))'))
			->joinLeft('ironagedenomxregion','ironagedenomxregion.regionID = geographyironage.id',array())
			->joinLeft('denominations','ironagedenomxregion.denomID = denominations.id',array())  
			->where('denominations.id = ?',$term)
			->order('region');
		$options = $this->getAdapter()->fetchAll($select);
		return $options;
    }

    /** Get Iron Age geographical regions as key value pairs for dropdown listing
	* @return array
	* @todo add caching
	*/
	public function getIronAgeGeographyDD() {
		$regions = $this->getAdapter();
		$select = $regions->select()
			->from($this->_name, array('id', 'term' => 'CONCAT(region,"  ",IFNULL(area,""),"  ",IFNULL(tribe,""))'))
			->where('valid = ?', (int)1)
			->order('region');
		$options = $this->getAdapter()->fetchPairs($select);
		return $options;
    }

    /** Get Iron Age geographical region by region id
	* @param integer $region
	* @return string array
	* @todo add caching
	*/
    public function getIronAgeDenomGeog($region) {
		$regions = $this->getAdapter();
		$select = $regions->select()
			->from($this->_name, array('id', 'area', 'region', 'tribe'))
			->joinLeft('ironagedenomxregion','ironagedenomxregion.regionID = geographyironage.id', array())  
			->joinLeft('denominations','denominations.id = ironagedenomxregion.denomID', array())
			->where('denominations.id = ?', (int)$region);
		$options = $this->getAdapter()->fetchAll($select);
		return $options;
	}

	/** Get Iron Age geographical regions for menu production by term
	* @param integer $term
	* @return string array
	* @todo add caching
	*/
	public function getIronAgeGeographyMenu($term){
        $regions = $this->getAdapter();
		$select = $regions->select()
            ->from($this->_name, array('id','term' =>'CONCAT(region," - ",area," - ",tribe)'))
			->joinLeft('ironagedenomxregion','ironagedenomxregion.regionID = geographyironage.id',array())
			->joinLeft('denominations','ironagedenomxregion.denomID = denominations.id',array())  
			->where('denominations.id = ?', (int)$term)
			->order('region');
	   $options = $this->getAdapter()->fetchPairs($select);
		return $options;
    }

    /** Get all Iron Age geographical regions 
	* @return string array
	* @todo add caching
	*/
	public function getIronAgeRegions() {
		$regions = $this->getAdapter();
		$select = $regions->select()
			->from($this->_name, array('id', 'region', 'area', 'tribe'))
			->order('area');
        return $regions->fetchAll($select);
    }
    
    /** Get all Iron Age geographical region by ID number
    * @param integer $id   
	* @return string array
	* @todo add caching
	*/
	public function getIronAgeRegion($id) {
		$regions = $this->getAdapter();
		$select = $regions->select()
						  ->from($this->_name, array('id','region','area','tribe'))
						  ->where('id = ?', (int)$id)
						  ->order('id ASC');
        return $regions->fetchAll($select);
    }
    
    /** Get all Iron Age geographical regions for admin interface
	* @return string array
	* @todo add caching
	*/
	public function getIronAgeRegionsAdmin() {
		$regions = $this->getAdapter();
		$select = $regions->select()
						  ->from($this->_name)
						  ->joinLeft('users','users.id = ' . $this->_name . '.createdBy',
						  array('fullname'))
						  ->joinLeft('users','users_2.id = ' . $this->_name.'.updatedBy', 
						  array('fn' => 'fullname'))
						  ->order('area');
        return $regions->fetchAll($select);
    }
    
     /** Get all Iron Age geographical regions to rulers
    * @param integer $id   
	* @return string array
	* @todo add caching
	*/
	public function getIronAgeRegionToRuler($id) {
		$regions = $this->getAdapter();
		$select = $regions->select()
						  ->from($this->_name,array('id','area','region'))
						  ->joinLeft('ironagerulerxregion','ironagerulerxregion.regionID = geographyironage.id', array())  
						  ->joinLeft('rulers','rulers.id = ironagerulerxregion.rulerID', array())
						  ->where('rulers.id = ?', (int)$id);
        return $regions->fetchAll($select);
	}
}
