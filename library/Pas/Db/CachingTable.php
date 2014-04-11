<?php
class Pas_Db_CachingTable extends Zend_Db_Table_Abstract
{
  /**
   * @var Zend_Cache
   */
  protected $_cache = null;

  /**
   * @var bool
   */
  public $cache_result = true;

  /**
   * Initialize
   */
  public function init()
  {
    // Get from bootstrap
    $this->_cache = Zend_Registry::get('Cache');
  } // /function

  /**
   * Reset cache
   */
  public function _purgeCache()
  {
    $this->_cache->clean(Zend_Cache::CLEANING_MODE_ALL);
  } // /function

  /**
   * update
   */
  public function update(array $data, $where)
  {
    parent::update($data, $where);
    $this->_purgeCache();
  } // /function

  /**
   * insert
   */
  public function insert(array $data)
  {
    parent::insert($data);
    $this->_purgeCache();
  } // /function

  /**
   * delete
   */
  public function delete($where)
  {
    parent::delete($where);
    $this->_purgeCache();
  } // /function

  /**
   * Fetch all
   */
  public function fetchAll($where = null, $order = null, $count = null, $offset = null)
  {
    $id = md5($where->__toString());

    if ((!($this->_cache->test($id))) || (!$this->cache_result))
    {
      $result = parent::fetchAll($where, $order, $count, $offset);
      $this->_cache->save($result);

      return $result;
    }
    else
    {
      return $this->_cache->load($id);
    }

  } // /function

  /**
   * Fetch one result
   */
  public function fetchRow($where = null, $order = null)
  {
    $id = md5($where->__toString());

    if ((!($this->_cache->test($id))) || (!$this->cache_result))
    {
      $result = parent::fetchRow($where, $order);
      $this->_cache->save($result);

      return $result;
    }
    else
    {
      return $this->_cache->load($id);
    }

  } // /function

} 