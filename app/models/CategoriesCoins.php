<?php
/** 
 * Model for constructing coin category relationships for Medieval period coinage
 * 
 * Example of use:
 * 
 * <code>
 * <?php
 * $model = new CategoriesCoins();
 * $data = $model->getCategoriesAll();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   	Pas
 * @package    	Pas_Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/AddMedievalTypeForm.php 
 * @uses Zend_Cache
 * @todo add caching
 */

class CategoriesCoins extends Pas_Db_Table_Abstract {

    /** Set the table name
     * @access protected
     */
    protected $_name = 'categoriescoins';

    /** Set the primary key
     * @access protected
     */
    protected $_primary = 'id';

    /** Get all valid category names
     * @access public
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
     * @access public
     * @return array of pairs
     */
    public function getPeriodEarlyMed() {
        $select = $this->select()
                ->from($this->_name, array('id', 'category'))
                ->where('periodID = ?', (int)47)
                ->order('id');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all valid category names by Medieval period for a dropdown
     * @access public
     * @return array of pairs
     */
    public function getPeriodMed() {
        $select = $this->select()
                ->from($this->_name, array('id', 'category'))
                ->where('periodID = ?', (int)29)
                ->order('id');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all valid category names by Post Medieval period for a dropdown
     * @access public
     * @return array of pairs
     */
    public function getPeriodPostMed() {
        $select = $this->select()
                ->from($this->_name, array('id', 'category'))
                ->where('periodID = ? ', (int)36)
                ->order('id');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all valid categories
     * @access public
     * @param int $type The type ID
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
     * @access public
     * @param int $period The period identifier
     * @return array
     */
    public function getCategoriesPeriod($period) {
        $cats = $this->getAdapter();
        $select = $cats->select()
                ->from($this->_name, array('id','term' => 'category'))
                ->joinLeft('periods','periods.id = ' . $this->_name 
                        . '.periodID', array())
                ->where($this->_name . '.periodID = ?', (int) $period)
                ->order('id');
        return $cats->fetchAll($select);
    }

    /** Get all valid categories by period for the administration interface
     * @access public
     * @param int $period The period ID
     * @return array
     */
    public function getCategoriesPeriodAdmin($period) {
        $cats = $this->getAdapter();
        $select = $cats->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name 
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name 
                        . '.updatedBy', array('fn' => 'fullname'))
                ->where($this->_name . '.periodID = ?', (int) $period)
                ->order('id');
        return $cats->fetchAll($select);
    }

    /** Get all categories for a dropdown listing
     * @access public
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
     * @access public
     * @param int $id The category identifier
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
     * @access public
     * @param int $categoryID The category ID
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
     * @access public
     * @param integer $period The period identifer
     * @return array
     */
    public function getCatsSiteMap($period) {
        $key = md5('sitemapcat' . $period);
        if (!$data = $this->_cache->load($key)) {
            $cats = $this->getAdapter();
            $select = $cats->select()
                    ->from($this->_name,array('id', 'category', 'updated'))
                    ->where($this->_name . '.periodID = ?', (int)$period)
                    ->order('id');
            $data =  $cats->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return  $data;
    }
}