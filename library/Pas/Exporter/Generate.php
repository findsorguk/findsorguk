<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Generate
 *
 * @author danielpett
 */
class Pas_Exporter_Generate {

    protected $_user;

    protected $_dateTime;

    protected $_memory;

    protected $_results;

    protected $_heroFields = array();

    protected $_kmlFields = array(
        'id', 'old_findID', 'description',
        'gridref', 'fourFigure', 'longitude',
        'latitude', 'county', 'woeid',
		'district', 'parish','knownas',
        'thumbnail');

    protected $_gisFields = array();

    protected $_higher = array('admin','flos','fa','treasure');

    protected $_intermediate = array('hero','research');

    protected $_lower = array('member');

    protected $_search;

    protected $_formats = array('csv','kml','hero','gis','report','nms');

    protected $_format;

    protected $_params;

    protected $_maxRows;

    protected $_uncleanParams = array('csrf','page','module','controller','action');

    public function __construct() {

        $user = new Pas_User_Details();
        $this->_user = $user->getPerson();

        $this->_dateTime = Zend_Date::now()->toString('yyyyMMddHHmmss');
        $backendOptions = array(
        'cache_dir' => APPLICATION_PATH . '/tmp'
        );

        $this->_memory = Zend_Memory::factory('File', $backendOptions);

        $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $this->_params = $this->_cleanParams($params);
        $this->_search = new Pas_Solr_Handler('beowulf');
    }

    /** Set the number of rows per export
     * @accees public
     * @param int $maxRows
     */
    public function setMaxRows($maxRows) {
        $this->_maxRows = $maxRows;
    }

    public function _cleanParams($params){
        if(is_array($params)){
            foreach($params as $k => $v){
                if(in_array($k, $this->_uncleanParams)){
                    unset($params[$k]);
                }
            }
            $params['format'] = 'json';
            return $params;
        } else {
            throw new Pas_Exporter_Exception('The parameters must be an array');
        }
    }

    public function getFormat() {
        return $this->_format;
    }

    public function setFormat($format) {
        if(in_array($format, $this->_formats)){
        $this->_format = $format;
        } else {
            throw new Pas_Exporter_Exception('That format is not allowed');
        }
    }



    public function getMaxRows() {
        return $this->_maxRows;
    }

    protected function _createOutput($format){
      $format = ucfirst(strtolower($format));
      $class = 'Pas_Exporter_' . $format;
      $output = new $class();
      return $output->create();
    }



    public function execute(){

        return $this->_createOutput($this->_format);
    }

}

