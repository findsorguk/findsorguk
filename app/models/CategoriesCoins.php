<?php
/** Model for constructing coin category relationships for Medieval period coinage
* @category   	Pas
* @package    	Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class CategoriesCoins
	extends Pas_Db_Table_Abstract {

	/** Set the table name
	 */
	protected $_name = 'categoriescoins';

	/** Set the primary key
	 */
	protected $_primary = 'id';

	/** Get all valid category names
	* @return array
	*/
	public function getCategoryName() {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name, array('id','term' => 'category'))
		->where('periodID = ?',(int)47);
	return $cats->fetchAll($select);
	}

	/** Get all valid category names by Early Medieval period for a dropdown
	* @return array of pairs
	*/
	public function getPeriodEarlyMed() {
	$select = $this->select()
		->from($this->_name, array('id', 'category'))
		->where('periodID = ?', (int)47)
		->order('id');
	$options = $this->getAdapter()->fetchPairs($select);
 	return $options;
    }

   	/** Get all valid category names by Medieval period for a dropdown
	* @return array of pairs
	*/
	public function getPeriodMed() {
	$select = $this->select()
		->from($this->_name, array('id', 'category'))
		->where('periodID = ?', (int)29)
		->order('id');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get all valid category names by Post Medieval period for a dropdown
	* @return array of pairs
	*/
	public function getPeriodPostMed() {
	$select = $this->select()
		->from($this->_name, array('id', 'category'))
		->where('periodID = ? ', (int)36)
		->order('id');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

	/** Get all valid categories
	* @return array
	*/

	public function getCategories($type) {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name, array('id','term' => 'category'))
		->joinLeft('medievaltypes','medievaltypes.categoryID = categoriesCoins.id', array())
		->where('medievaltypes.rulerID = ?', (int)$type)
  		->order('medievaltypes.id')
		->limit(1);
	return $cats->fetchAll($select);
    }


    /** Get all valid categories by period
    * @param integer $period
	* @return array
	*/
	public function getCategoriesPeriod($period) {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name, array('id','term' => 'category'))
		->joinLeft('periods','periods.id = ' . $this->_name . '.periodID', array())
		->where($this->_name . '.periodID = ?', (int) $period)
		->order('id');
	return $cats->fetchAll($select);
    }

     /** Get all valid categories by period for the administration interface
    * @param integer $period
	* @return array
	*/
	public function getCategoriesPeriodAdmin($period) {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy', array('fn' => 'fullname'))
		->where($this->_name . '.periodID = ?', (int) $period)
		->order('id');
	return $cats->fetchAll($select);
    }

	/** Get all categories for a dropdown listing
	* @return array
	*/
	public function getCategoriesAll() {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name, array('id', 'term' => 'category'))
		->order($this->_primary);
	return $cats->fetchPairs($select);
    }

     /** Get category by ID number
    * @param integer $id
	* @return array
	*/
	public function getCategory($id) {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name, array('id', 'term' => 'category'))
		->where('id = ?', (int)$id);
	return $cats->fetchAll($select);
    }

     /** Get all valid rulers for a specific category
    * @param integer $categoryID
	* @return array
	*/
	public function getMedievalRulersToType($categoryID) {
    $key = md5('medtyperuler' . $categoryID);
            if (!$data = $this->_cache->load($key)) {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name, array('id','term' => 'category'))
		->joinLeft('medievaltypes','medievaltypes.categoryID = categoriescoins.ID', array())
		->joinLeft('rulers','rulers.id = medievaltypes.rulerID',
		array('id', 'issuer', 'date1', 'date2'))
		->where('medievaltypes.categoryID = ?',(int)$categoryID)
		->group('rulers.id');
	$data =  $cats->fetchAll($select);
        $this->_cache->save($data, $key);
    }
    return $data;
	}

	/** Get all valid categories for the sitemap by period
    * @param integer $period
	* @return array
	*/
	public function getCatsSiteMap($period) {
	if (!$data = $this->_cache->load('sitemapcat'.$period)) {
	$cats = $this->getAdapter();
	$select = $cats->select()
		->from($this->_name,array('id', 'category', 'updated'))
		->where($this->_name . '.periodID = ?', (int)$period)
		->order('id');
	$data =  $cats->fetchAll($select);
	$this->_cache->save($data, 'sitemapcat' . $period);
	}
	return  $data;
	}

}

