<?php

class Pas_Application extends Zend_Application {
        /**
         * Flag used when determining if we should cache our configuration.
         */
        protected $_cacheConfig = false;
 
        /**
         * Our default options which will use File caching
         */
        protected $_cacheOptions = array(
            'frontendType' => 'File',
            'backendType' => 'File',
            'frontendOptions' => array(),
            'backendOptions' => array()
        );
 
        /**
         * Constructor
         *
         * Initialize application. Potentially initializes include_paths, PHP
         * settings, and bootstrap class.
         *
         * When $options is an array with a key of configFile, this will tell the
         * class to cache the configuration using the default options or cacheOptions
         * passed in.
         *
         * @param  string                   $environment
         * @param  string|array|Zend_Config $options String path to configuration file, or array/Zend_Config of configuration options
         * @throws Zend_Application_Exception When invalid options are provided
         * @return void
         */
        public function __construct($environment, $options = null)
        {
            if (is_array($options) && isset($options['configFile'])) {
                $this->_cacheConfig = true;
 
                // First, let's check to see if there are any cache options
                if (isset($options['cacheOptions']))
                    $this->_cacheOptions =
                        array_merge($this->_cacheOptions, $options['cacheOptions']);
 
                $options = $options['configFile'];
            }
            parent::__construct($environment, $options);
        }
 
        /**
         * Load configuration file of options.
         *
         * Optionally will cache the configuration.
         *
         * @param  string $file
         * @throws Zend_Application_Exception When invalid configuration file is provided
         * @return array
         */
        protected function _loadConfig($file)
        {
            if (!$this->_cacheConfig)
                return parent::_loadConfig($file);
 
            require_once 'Zend/Cache.php';
            $cache = Zend_Cache::factory(
                $this->_cacheOptions['frontendType'],
                $this->_cacheOptions['backendType'],
                array_merge(array( // Frontend Default Options
                    'master_file' => $file,
                    'automatic_serialization' => true
                ), $this->_cacheOptions['frontendOptions']),
                array_merge(array( // Backend Default Options
                    'cache_dir' => APPLICATION_PATH . '/data/cache'
                ), $this->_cacheOptions['backendOptions'])
            );
 
            $config = $cache->load('Zend_Application_Config');
            if (!$config) {
                $config = parent::_loadConfig($file);
                $cache->save($config, 'Zend_Application_Config');
            }
 
            return $config;
        }
    }