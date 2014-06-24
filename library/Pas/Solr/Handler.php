<?php
/**
 * Solr handler class for retrieving data from the solr indexes
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Solr
 * @subpackage Handler
 * @uses Pas_Solr_Exception
 * @uses Solarium_Client
 *
 */
class Pas_Solr_Handler {
    
    /** The default location for the schema file
     * @access protected
     * @var string
     */
    protected $_schemaFile = '/conf/schema.xml';

    /** The schema path
     * @access protected
     * @var string
     */
    protected $_schemaPath = '/var/solr/';
    
    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;
    
    /** The solr config object
     * @access protected
     * @var type 
     */
    protected $_solrConfig;
    
    /** The config object
     * @access protected
     * @var \Zend_Config
     */
    protected $_config;
    
    /** The default core
     * @access protected
     * @var string
     * @todo change option when we rename cores
     */
    protected $_core = 'beowulf';
    
    /** The solr object
     * @access protected
     * @var \Solarium_Client
     */
    protected $_solr;

    /** The formats available for output
     * @access protected
     * @var array
     */
    protected $_formats = array(
        'json', 'csv', 'xml',
        'midas', 'rdf', 'n3',
        'rss', 'atom', 'kml',
    	'pdf', 'geojson', 'sitemap');
    
    /** The array of allowed higher level access
     * @access protected
     * @var array
     */
    protected $_allowed = array('fa','flos','admin','treasure', 'research');
    
    /** The default map option
     * @access protected
     * @var boolean
     */
    protected $_map = false;
    
    /** The load balancer plugin
     * @access protected
     * @var object
     */
    protected $_loadbalancer;
    
    /** The default set of fields to query
     * @access protected
     * @var array
     */    
    protected $_fields = array('*');
    
    /** The array of fields in a schema
     * @access protected
     * @var array
     */
    protected $_schemaFields = array();
    
    /** The array of cores in the system
     * @access protected
     * @var array
     */
    protected $_cores = array();
    
    /** The array of parameters to query
     * @access protected
     * @var array
     */
    protected $_params = array();
    
    /** The array of fields to highlight
     * @access protected
     * @var array
     */
    protected $_highlights = array();
    
    protected $_index;

    protected $_limit;

    protected $_facets;

    protected $_format;

    

    

    protected $_facetFields;

    protected $_facetSet;

    protected $_query;

    protected $_stats = false;

    protected $_statsFields = array('quantity');

    


    /** Get the schema path
     * @access public
     * @return string
     */
    public function getSchemaPath() {
        return $this->_schemaPath;
    }

    /** Set a different schema path
     * @access public
     * @param directory $schemaPath
     * @return \Pas_Solr_Handler
     */
    public function setSchemaPath($schemaPath) {
        if(is_dir($schemaPath)){
            $this->_schemaPath = $schemaPath;
        } else {
            throw new Zend_Exception('That path does not exist', 500);
        }
        return $this;
    }

    /** Get the schema file
     * @access public
     * @return string
     */
    public function getSchemaFile() {
        return $this->_schemaFile;
    }

    /** Set the Schema file
     * @access public
     * @param string $schemaFile
     * @return \Pas_Solr_Handler
     * @throws Zend_Exception
     */
    public function setSchemaFile($schemaFile) {
        if(is_file($this->getSchemaPath() . $this->getCore() . $schemaFile)) {
        $this->_schemaFile = $schemaFile;
        } else {
            throw new Zend_Exception('That schema file does not exist', 500);
        }
        return $this;
    }
                
    /** Set the field on which to generate stats
     * @access public
     * @param string $value
     * @return string
     */
    public function setStats($value){
    	return $this->_stats = $value;
    }

    /** Get the field on which to generate stats
     * @access public
     * @return type
     */
    public function getStats(){
    	return $this->_stats;
    }
    
    /** Set whether the function is producing mapping data
     * @access public
     * @param string $map
     * @return \Pas_Solr_Handler
     */
    public function setMap($map){
    	$this->_map = $map;
        return $this;
    }

    /** Get whether map is true or false
     * @access public
     * @return boolean
     */
    public function getMap() {
        return $this->_map;
    }

    /** Set the field on which to get stats
     * @access public
     * @param array $fields
     * @return \Pas_Solr_Handler
     */
    public function setStatsFields(array $fields){
        if(is_array($fields)){
            $this->_statsFields = $fields;
        } 
        return $this;
    }

    /** Get the stats field array
     * @access public
     * @return array
     */
    public function getStatsFields(){
    	return $this->_statsFields;
    }

    /** Return the formats that can be queried
     * @access public
     * @return array
     */
    public function getFormats() {
        return $this->_formats;
    }

    /** Get the allowed array
     * @access public
     * @return array
     */
    public function getAllowed() {
        return $this->_allowed;
    }
        
    /** Get the cache object
     * @access public
     * @return \Zend_Cache
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config object
     * @access public
     * @return \Zend_Config
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }
    
    /** Get the core to search
     * @access public
     * @return string
     */
    public function getCore() {
        return $this->_core;
    }

    /** Set the core to search upon
     * @access public
     * @param string $core
     * @return \Pas_Solr_Handler
     */
    public function setCore($core) {
        $this->_core = $core;
        return $this;
    }

    /** Get the solr config options
     * @access public
     * @return array
     */
    public function getSolrConfig() {
        $config = $this->getConfig()->solr->master->toArray();
        $config['core'] = $this->getCore();
        return $this->_solrConfig = array('adapteroptions' => $config);
    }

    /** Get the solr object for querying cores
     * @access public
     * @return \Solarium_Client
     */
    public function getSolr() {
        $this->_solr = new Solarium_Client($this->getSolrConfig());
        $this->_solr->setAdapter('Solarium_Client_Adapter_ZendHttp');
        $this->_solr->getAdapter()->getZendHttp();
        return $this->_solr;
    }

    /** Get the load balancer
     * @access public
     * @return type
     */
    public function getLoadbalancer() {
        $loadbalancer = $this->getSolr()->getPlugin('loadbalancer');
        $master = $this->getConfig()->solr->master->toArray();
        $asgard  = $this->getConfig()->solr->asgard->toArray();
        $valhalla = $this->getConfig()->solr->valhalla->toArray();
        $loadbalancer->addServer('beowulf', $master, 100);
	$loadbalancer->addServer('asgard', $asgard, 200);
	$loadbalancer->addServer('valhalla', $valhalla, 150);
	$loadbalancer->setFailoverEnabled(true);
        $this->_loadbalancer = $loadbalancer;
        return $this->_loadbalancer;
    }

                    
    protected function _setFacetFieldsAvailable(){
        $facetFields = array();
        foreach($this->_schemaFields as $k => $v){
            $facetFields[$k] = 'fq' . $v;
        }
        $this->_facetFields = $facetFields;
        return $this;
    }

    
    public function __construct($core){
    $this->_checkFieldList($this->getCore(), $this->setFields());
    $this->_checkCoreExists();
    $this->_getSchemaFields();
    }

    /** Get the cores available from directory
     * @access public
     * @return array
     */
    public function getCores() {
        if (!($this->getCache()->test('solrCores'))) {
            $dir = new DirectoryIterator($this->getSchemaPath());
            $cores = array();
            foreach ($dir as $dirEntry) {
                if($dirEntry->isDir() && !$dirEntry->isDot()){
                    $cores[] = $dirEntry->getFilename();
                }
            }
            $this->getCache()->save($cores);
        } else {
            $cores = $this->getCache()->load('solrCores');
        }
        return $cores;
    }

    /** Get the fields in a schema
     * @access public
     * @return array
     */
    public function getSchemaFields(){
        $file = $this->getSchemaPath() . $this->getCore() . $this->getSchemaFile();
        $key = md5($file);
        if (!($this->getCache()->test($key))) {
            if(file_exists($file)){
                $xml = simplexml_load_file($file);
                $schemaFields = array();
                foreach($xml->fields->field as $field){
                    $string = get_object_vars($field->attributes());
                    //This bit looks honky, couldn't get it to work with object notation
                    $schemaFields[] = $string["@attributes"]['name'];
                }
            }
            $this->getCache()->save($schemaFields);
        } else {
            $schemaFields = $this->getCache()->load($key);
        }
        $this->_schemaFields = $schemaFields;
        return $this->_schemaFields;
    }

    /** Check if the core exists
     * @access public
     * @return boolean
     * @throws Pas_Solr_Exception
     */
    protected function _checkCoreExists(){
        if(!in_array($this->getCore(),$this->getCores())){
            throw new Pas_Solr_Exception('That is not a valid core',500);
        } else {
            return true;
        }
    }
    
    /** Get the user's role
     * @access public
     * @return string
     */
    public function getRole(){
        $user = new Pas_User_Details();
        return $user->getRole();
    }

    /** Get the list of fields to query
     * @access public
     * @return array
     */
    public function getFields(){
        return $this->_fields;
    }

    /** Set the fields to return
     *
     * @param array $fields
     * @return type
     */
    public function setFields( array $fields){
        if(is_array($fields)){
            $this->_fields = $fields;
        } 
        return $this->_fields;
    }

    /** Set the parameters to use
     * @access public
     * @param array $params
     * @return array
     */
    public function setParams(array $params){
    	if(is_array($params)){
            $this->_params = $this->filterParams($params);
    	}
        return $this->_params;
    }
    
    /** Get the parameters to query
     * @access public
     * @return array
     */
    public function getParams() {
        return $this->_params;
    }

    
    /** Filter parameters being passed to search
     * @access public
     * @param array $params
     * @return array
     */
    public function filterParams(array $params){
    	if(array_key_exists('created', $params)){
    		$created = $params['created'];
    		$queryDateA = $created . "T00:00:00.001Z";
    		$queryDateB = $created . "T23:59:59.99Z";
    		$params['created'] = $queryDateA . ' TO ' . $queryDateB ;
    	}
     	if(array_key_exists('updated', $params)){
    		$updated = $params['updated'];
    		$queryDateA = $updated . "T00:00:00.001Z";
    		$queryDateB = $updated . "T23:59:59.99Z";
    		$params['updated'] = $queryDateA . ' TO ' . $queryDateB ;
    	}
    	if(array_key_exists('createdBefore', $params) && array_key_exists('createdAfter', $params)){
    		$queryDateA = $params['createdAfter'] . "T00:00:00.001Z";
    		$queryDateB = $params['createdBefore'] . "T23:59:59.99Z";
    		$params['created'] = $queryDateA . ' TO ' . $queryDateB;
    		unset($params['createdBefore']);
    		unset($params['createdAfter']);
    	}
    	if(array_key_exists('createdBefore', $params)){
    		$queryDate = $params['createdBefore'] . "T23:59:59.99Z";
    		$params['created'] = '* TO ' . $queryDate;
    		unset($params['createdBefore']);
    	}
    	if(array_key_exists('createdAfter', $params)){
    		$queryDate = $params['createdAfter'] . "T00:00:00.001Z";
    		$params['created'] = $queryDate . ' TO * ';
    		unset($params['createdAfter']);
    	}

    	if(array_key_exists('updatedBefore', $params) && array_key_exists('updatedAfter', $params)){
    		$queryDateA = $params['updatedAfter'] . "T00:00:00.001Z";
    		$queryDateB = $params['updatedBefore'] . "T23:59:59.99Z";
    		$params['updated'] = $queryDateA . ' TO ' . $queryDateB;
    		unset($params['updatedBefore']);
    		unset($params['updatedAfter']);
    	}
    	if(array_key_exists('updatedBefore', $params)){
    		$queryDate = $params['updatedBefore'] . "T23:59:59.99Z";
    		$params['updated'] = '* TO ' . $queryDate;
    		unset($params['updatedBefore']);
    	}
    	if(array_key_exists('updatedAfter', $params)){
    		$queryDate = $params['updatedAfter'] . "T00:00:00.001Z";
    		$params['updated'] = $queryDate . ' TO NOW ';
    		unset($params['updatedAfter']);
    	}
    	foreach($params as $k => $v){
    		if(is_null($v) || ($v === '')){
    			unset($params[$k]);
    		}
    	}

    	return $params;

    }

    /** Set fields to highlight
     * @access public
     * @param array $highlights
     * @return array
     */
    public function setHighlights(array $highlights){
        if(is_array($highlights)){
            $this->_highlights = $highlights;
        }
        return $this->_highlights;
    }

    /** Create highlighting
     *
     * @return array
     */
    protected function _createHighlighting(){
    $hl = $this->_query->getHighlighting();
    $hl->setFields(implode($this->_highlights,','));
    $hl->setSimplePrefix('<span class="hl">');
    $hl->setSimplePostfix('</span>');
    return $hl;
    }

    /** Get the highlights back
     *
     * @return type
     */
    public function getHighlights(){
        if($this->_highlights){
            return $this->_resultset->getHighlighting();
        }
    }

    protected function _createFilters( array $params){
        if(is_array($params)){
            if(array_key_exists('d', $params) && array_key_exists('lon',$params) && array_key_exists('lat',$params)){
                if(!is_null($params['d']) && !is_null($params['lon']) && !is_null($params['lat'])){
                    $helper = $this->_query->getHelper();
                    $this->_query->createFilterQuery('geo')->setQuery(
                            $helper->geofilt(
                                    $params['lat'],
                                    $params['lon'],
                                    'coordinates',
                                    $params['d'])
                            );
                    }
                }
                $map = $this->getMap();
                if(($map === true) && !in_array($this->getRole(), 
                        $this->getAllowed()) && ($this->getCore() === 'beowulf')){
                    $this->_query->createFilterQuery('knownas')->setQuery('-knownas:["" TO *]');
                    $this->_query->createFilterQuery('hascoords')->setQuery('gridref:["" TO *]');
                } elseif($map === true && ($this->getCore() === 'beowulf')) {
                    $this->_query->createFilterQuery('hascoords')->setQuery('gridref:["" TO *]');
                }
                if(array_key_exists('bbox',$params)){
                    $coords = new Pas_Solr_BoundingBoxCheck($params['bbox']);
                    $bbox = $coords->checkCoordinates();
                    $this->_query->createFilterQuery('bbox')->setQuery($bbox);
                }
                foreach($params as $key => $value){
                    if(!in_array($key, $this->_schemaFields))   {
                        unset($params[$key]);
                    }
                }
                if(isset($params['thumbnail'])){
                    $this->_query->createFilterQuery('thumbnails')->setQuery('thumbnail:[1 TO *]');
                    unset($params['thumbnail']);
                }
                $this->_checkFieldList($this->_core, array_keys($params));
                foreach($params as $key => $value){
                    $this->_query->createFilterQuery($key . $value)->setQuery($key . ':"'
                            . $value . '"');
                }
                } else {
                    throw new Pas_Solr_Exception('The search params must be an array');
                }
    }

    /** Set the facets array up
     * @access public
     * @param array $facets
     * @return \Pas_Solr_Handler
     */
    public function setFacets(array $facets){
    	if(is_array($facets)){
            $this->_setFacetFieldsAvailable($facets);
            $this->_facets = $facets;
    	}
        return $this;
    }

    /** Get the number of results from a result set
     * @access public
     * @return int
     */
    public function getNumber(){
        return $this->_resultset->getNumFound();
    }

    /** Create a pagination object
     * @access public
     * @return type
     */
    public function _createPagination(){
        $paginator = Zend_Paginator::factory($this->_resultset->getNumFound());
        $paginator->setCurrentPageNumber($this->getPage($this->_params))
                ->setItemCountPerPage($this->_getRows($this->_params))
                ->setPageRange(10);
        return $paginator;
    }

    /** Pricess the results of the query
     *
     * @return array $data
     */
    public function _processResults(){
    $data = array();
    foreach($this->_resultset as $doc){
	$fields = array();
	foreach($doc as $key => $value){
            $fields[$key] = $value;
            }
    	$data[] = $fields;
    }

    if($this->_format != 'kml'){
    $processor = new Pas_Solr_SensitiveFields();
    $clean = $processor->cleanData($data, $this->getRole(), $this->_core);
    } else {
    	$clean = $data;
    }
    $return = array();
    foreach($clean as $d){
        if(array_key_exists('_version_', $d)){
            unset($d['_version_']);

        }
        $return[] = $d;
    }


    return $return;
    }

    /** Process stats for a query
     * @access public
     * @return array
     */
    public function _processStats(){
        $stats = $this->_resultset->getStats();
        foreach($stats as $stat){
            $data = array(
                'stdDeviation' => $stat->getStddev(),
                'mean' => $stat->getMean(),
                'sum' => $stat->getSum(),
                'query' => $stat->getName(),
                'minima' => $stat->getMin(),
                'maxima' => $stat->getMax(),
		'count' =>  $stat->getCount(),
                'missing' => $stat->getMissing(),
                'sumOfSquares' => $stat->getSumOfSquares(),
                'mean' => $stat->getMean()
                );
        }
        return $data;
    }

    public function _processFacets(){
        if($this->_facets){
            $facetData = array();
            foreach($this->_facets as $k){
                $facetData[$k] = array();
                $facet = $this->_resultset->getFacetSet()->getFacet($k);
                if($facet){
                    foreach($facet as $value => $count) {
                        $facetData[$k][ $value ]  = $count;
                    }
                }
            }
        return $facetData;
        } else {
            return false;
        }
    }

    /** Check the field list works by core
     *
     * @param string $core
     * @param array $fields
     * @throws Pas_Solr_Exception
     */
    protected function _checkFieldList($core = 'beowulf',  $fields){
        if(!is_null($fields)){
            $this->_schemaFields[] = '*';
            $this->_schemaFields[] = 'q';
            foreach($fields as $field){
                if(!in_array($field, $this->_schemaFields)){
                    $message = 'The field ' . $field . ' is not in the schema';
                    throw new Pas_Solr_Exception( $message, 500);
                }
            }
        } else {
            throw new Pas_Solr_Exception('The fields supplied are not an array');
        }
    }

    protected function _getSort($core, $params){
        if(array_key_exists('sort',$params)){
            $this->_checkFieldList($core, array($params['sort']));
            $field = $params['sort'];
        } else {
            $field = 'created';
        }
        $allowed = array('desc','asc');
        if(array_key_exists('direction', $params)) {
            if(in_array($params['direction'],$allowed)){
                $direction = $params['direction'];
            } else {
                $message = 'That directional sort does not exist';
                throw new Pas_Solr_Exception($message, 500);
            }
        } else {
            $direction = 'desc';
        }
        return array($field => $direction);
    }

    /** Get the rows
     * @access public
     * @param type $params
     * @return int
     */
    public function _getRows($params){
    if(isset($params['show']) && in_array($this->_format, array('json', 'xml', 'geojson', null))){
		$rows = $params['show'];
        if($rows > 100){
            $rows = 100;
        }
    } elseif($this->_format === 'kml'){
    	if(!isset($params['show'])){
    	$rows = 1200;
    	} else {
    	$rows = $params['show'];
    	}
    } elseif($this->_format === 'pdf'){
    	$rows = 500;
    } elseif($this->_format === 'sitemap'){
    	$rows = 1000;
    } else {
        $rows = 20;
    }
    return $rows;
    }

    /** Get the starting row
     * @access public
     * @param array $params
     * @return int
     */
    public function _getStart(array $params){
    if(array_key_exists('page', $params) && !is_null($params['page'])){
        $start = ($params['page'] - 1) * $this->_getRows($params);
    } else {
        $start = 0;
    }
    return abs($start);
    }

    /** Get the starting page
     * @access public
     * @param array $params
     * @return int
     */
    public function getPage(array $params){
    if(array_key_exists('page', $params)){
        $page = $params['page'];
    } else {
        $page = 1;
    }
    return $page;
    }

    /** Execute the query
     * @access public
     * @return object
     */
    public function execute(){
    $select = array(
    'query'         => '*:*',
//    'fields'        => array('*'),
    'filterquery' => array(),
    );
	$select['fields'] = $this->getFields();
    $select['sort'] = $this->_getSort($this->_core, $this->_params);

    $select['start'] = $this->_getStart($this->_params);

    if(array_key_exists('format', $this->_params)){
    $this->_processFormats($this->_params);
    }

    $select['rows'] = $this->_getRows($this->_params);

    if(array_key_exists('q',$this->_params)){
    $select['query'] = $this->_params['q'];
            unset($this->_params['q']);
    }

    // get a select query instance based on the config
    $this->_query = $this->_solr->createSelect($select);

    if(array_key_exists('created', $this->_params)){
    $this->_query->createFilterQuery('created')->setQuery('created:[' . $this->_params['created'] .']');
    unset($this->_params['created']);
    }

    if(array_key_exists('updated', $this->_params)){
    $this->_query->createFilterQuery('updated')->setQuery('updated:[' . $this->_params['updated'] . ']');
    unset($this->_params['updated']);
    }

    if(array_key_exists('todate', $this->_params) && array_key_exists('fromdate', $this->_params)){
    $this->_query->createFilterQuery('range')->setQuery('todate:[' . $this->_params['fromdate'] .  ' TO ' . $this->_params['todate'] . ']');
    $this->_query->createFilterQuery('rangedate')->setQuery('fromdate:[' . $this->_params['fromdate'] .  ' TO ' . $this->_params['todate'] . ']');
    unset($this->_params['todate']);
	unset($this->_params['fromdate']);
    }

    if(array_key_exists('fromdate', $this->_params)){
    $this->_query->createFilterQuery('datefrom')->setQuery('fromdate:[' . $this->_params['fromdate'] . ' TO * ]');
    unset($this->_params['fromdate']);
    }

    if(array_key_exists('todate', $this->_params)){
    $this->_query->createFilterQuery('todate')->setQuery('todate:[* TO ' . $this->_params['todate'] . ']');
    unset($this->_params['todate']);
    }
  	//Statistics are only enabled in this instance for the finds index
 	if($this->_core === 'beowulf'){
		$stats = $this->_query->getStats();
		foreach($this->getStatsFields() as $field){
			$stats->createField($field);
		}
 	}
    if(!in_array($this->getRole(), $this->getAllowed()) || is_null($this->getRole()) ) {
    if(array_key_exists('workflow', array_flip($this->_schemaFields))){
    $this->_query->createFilterQuery('workflow')->setQuery('workflow:[3 TO 4]');
    }
    if((array_key_exists('parish', $this->_params) || array_key_exists('fourFigure', $this->_params)) && ($this->_core === 'beowulf')){
    $this->_query->createFilterQuery('knownas')->setQuery('-knownas:["" TO *]');
	}

	if($this->_format === 'kml' && ($this->_core === 'beowulf')){
    $this->_query->createFilterQuery('knownas')->setQuery('-knownas:["" TO *]');
    $this->_query->createFilterQuery('geopresent')->setQuery('gridref:[* TO *]');
	}

    }

    if(!is_null($this->_facets)){
    	$this->_createFacets($this->_facets);
        foreach($this->_params as $k => $v){
        if(in_array($k,$this->_facetFields)){

            $this->_buildFacetQueries($k,$v);
            unset($this->_params['k']);
        }

    }
    }

    $this->_createFilters($this->_params);

    $this->_resultset = $this->_solr->select($this->_query);
    return $this->_resultset;
    }



    /**
     * Create a facet query based on the key value pairs of an array
     * @param string $k
     * @param string $v
     */
    protected function _buildFacetQueries($k, $v){
        return $this->_query->createFilterQuery($k)->setQuery(substr($k, 2) . ':"' . $v . '"');
    }

    /**
     * Debug a query
     *
     */
    public function debugQuery(){
    Zend_Debug::dump($this->_params,'The params sent');
    Zend_Debug::dump($this->_query, 'The Query');
    Zend_Debug::dump($this->_fields, 'The field list');
    Zend_Debug::dump($this->_schemaFields, 'The schema fields');
    Zend_Debug::dump($this->_formats, 'The format called');
    }

    /**
     * Debug processing of a query
     */
    public function debugProcessing(){
    Zend_Debug::dump($this->_createPagination($this->_resultset), 'The pagination');
    Zend_Debug::dump($this->_processResults($this->_resultset), 'The processed results');
    Zend_Debug::dump($this->_processFacets($this->_resultset, $this->_facets),'The facet set');
    Zend_Debug::dump($this->_processStats(), 'The statistics associated');
    }


    /**
     * Create the facets
     *
     */
    protected function _createFacets(){
    $this->_checkFieldList($this->_core, $this->_facets);
    $facetSet = $this->_query->getFacetSet();
    $facetSet->setMinCount(1);
    $facetSet->setLimit(-1);
    $facetSet->setSort('count');
        foreach($this->_facets as $key){
            $facetSet->createFacetField($key)->setField($key);
        }
    }
    /**
     * Return the loadbalancer key
     * @return string
     * @access public
     */
    public function getLoadBalancerKey() {
    	return $this->getLoadbalancer()->getLastServerKey();
    }

	

    /** Process format key
     * @access protected
     * @return boolean
     */
    protected function _processFormats(){
        $format = $this->_params['format'];
        if(in_array($format, $this->_formats)){
            return $this->_format = $format;
        } else {
            return false;
        }
    }
}