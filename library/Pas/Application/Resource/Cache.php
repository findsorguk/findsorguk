<?php

class Pas_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{
  /**
   * Default registry key
   */
  const DEFAULT_REGISTRY_KEY = 'Pas_Cache';

  /**
   * Cache instance
   *
   * @var Zend_Cache
   */
  protected $_cache = null;

  /**
   * Inititalize cache resource
   *
   * @return Zend_Cache
   */
  public function init ()
  {
    return $this->getCache();
  }

  /**
   * Return cache instance
   *
   * @return Zend_Cache
   */
  public function getCache ()
  {
    if (null === $this->_cache) {
      $options = $this->getOptions();

      /// create cache instance
      $this->_cache = Zend_Cache::factory(
        $options['frontend']['adapter'],
        $options['backend']['adapter'],
        $options['frontend']['params'],
        $options['backend']['params']
      );

      /// use as default database metadata cache
      if (isset($options['isDefaultMetadataCache']) && true === (bool) $options['isDefaultMetadataCache']) {
        Zend_Db_Table_Abstract::setDefaultMetadataCache($this->_cache);
      }

      /// use as default translate cache
      if (isset($options['isDefaultTranslateCache']) && true === (bool) $options['isDefaultTranslateCache']) {
        Zend_Translate::setCache($this->_cache);
      }

      /// use as default locale cache
      if (isset($options['isDefaultLocaleCache']) && true === (bool) $options['isDefaultLocaleCache']) {
        Zend_Locale::setCache($this->_cache);
      }

      /// add to registry
      $key = (isset($options['registry_key']) && !is_numeric($options['registry_key'])) ? $options['registry_key'] : self::DEFAULT_REGISTRY_KEY;
      Zend_Registry::set($key, $this->_cache);
    }
    return $this->_cache;
  }
}