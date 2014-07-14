<?php
/** A class for caching db results
 * Not used at present.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @license http://URL name
 * @version 1
 * @uses Zend_Cache
 */
class Pas_Db_Table_Caching extends Zend_Db_Table_Abstract {
  
   /** The cache
    *  @var Zend_Cache
    */
    protected $_cache = null;

    /** Set whether to cache result
     * @access public
     * @var boolean
     */
    public $cache_result = true;
    
    /** Get the cache
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('Cache');
        return $this->_cache;
    }
    /** Reset the cache
     * @access public
     * @return \Pas_Db_Table_Caching
     */
    public function _purgeCache() {
        $this->getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
        return $this;
    } // /function

    /** Update data
     * @access public
     * @param array $data
     * @param array $where
     * @return \Pas_Db_Table_Caching
     */
    public function update(array $data, $where) {
        parent::update($data, $where);
        $this->_purgeCache();
        return $this;
    } 

    /** Insert data
     * @access public
     * @param array $data
     * @return \Pas_Db_Table_Caching
     */
    public function insert(array $data) {
        parent::insert($data);
        $this->_purgeCache();
        return $this;
    }

    /** Delete the data
     * @@access public
     * @param array $where
     * @return \Pas_Db_Table_Caching
     */
    public function delete($where) {
        parent::delete($where);
        $this->_purgeCache();
        return $this;
    } // /function

    /** Fetch all data
     * @access public
     * @param array $where
     * @param string $order
     * @param string $count
     * @param integer $offset
     * @return array
     */
    public function fetchAll($where = null, $order = null, $count = null, $offset = null) {
        $id = md5($where->__toString());
        if ((!($this->_cache->test($id))) || (!$this->cache_result))  {
            $result = parent::fetchAll($where, $order, $count, $offset);
            $this->_cache->save($result);
            return $result;
        } else {
            return $this->_cache->load($id);
        }
    } 

    /** Fetch one result
     * @access public
     * @param array $where
     * @param string $order
     * @return Object
     */
    public function fetchRow($where = null, $order = null) {
        $id = md5($where->__toString());
        if ((!($this->_cache->test($id))) || (!$this->cache_result)) {
            $result = parent::fetchRow($where, $order);
            $this->_cache->save($result);
            return $result;
        } else {
            return $this->_cache->load($id);
        }
    } // /function
} 