<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Solr handler class for retrieving data from the solr indexes
 * @category Pas
 * @package Pas_Solr
 * @subpackage Handler
 * @uses Pas_Solr_Exception
 * @uses Solarium_Client
 * @todo Schema path is hard coded....
 *
 * @author Daniel Pett
 */
define('SCHEMA_PATH', SOLR_PATH);

define('SCHEMA_FILE', '/conf/schema.xml' );

class Pas_Solr_ExportHandler {

    protected $_solr;

    protected $_index;

    protected $_limit;

    protected $_cache;

    protected $_config;

    protected $_solrConfig;

    protected $_facets;

    protected $_allowed = array('fa','flos','admin','treasure', 'research');

    protected $_formats = array(
        'json', 'csv', 'xml',
        'midas', 'rdf', 'n3',
    	'kml', 'turtle', 'geoJSON');

    protected $_format;

    protected $_schemaFields;

    protected $_params;

    protected $_facetFields;

    protected $_facetSet;

    protected $_query;


    protected function _setFacetFieldsAvailable(){
        $facetFields = array();
        foreach($this->_schemaFields as $k => $v){
            $facetFields[$k] = 'fq' . $v;
        }
        $this->_facetFields = $facetFields;
        return  $this->_facetFields;
    }

    public function __construct($core){
    $this->_cache = Zend_Registry::get('cache');
    $this->_config = Zend_Registry::get('config');
    $this->_core = $core;
    $this->_solrConfig = $this->_setSolrConfig($this->_core);
    $this->_solr = new Solarium_Client($this->_solrConfig);
    $this->_solr->setAdapter('Solarium_Client_Adapter_ZendHttp');
    $this->_checkFieldList($this->_core, $this->setFields());
    $this->_checkCoreExists();
    $this->_getSchemaFields();
    }

    /** Get the cores available from directory
     * Cache respose
     * @return array
     */
    private function _getCores() {
    if (!($this->_cache->test('solrCores'))) {
    $dir = new DirectoryIterator(SCHEMA_PATH);
    $cores = array();
    foreach ($dir as $dirEntry) {
            if($dirEntry->isDir() && !$dirEntry->isDot()){
                    $cores[] = $dirEntry->getFilename();
            }
    }
    $this->_cache->save($cores);
    } else {
    $cores = $this->_cache->load('solrCores');
    }
    return $cores;
    }


    /** Retrieve the Schema's fields and cache
     *
     * @return array
     */
    private function _getSchemaFields(){
    $file = SCHEMA_PATH . $this->_core . SCHEMA_FILE;
    $key = md5($file);
    if (!($this->_cache->test($key))) {
    if(file_exists($file)){
    $xml = simplexml_load_file($file);
    $schemaFields = array();
    foreach($xml->fields->field as $field){
        $string = get_object_vars($field->attributes());
        //This bit looks honky, couldn't get it to work with object notation
        $schemaFields[] = $string["@attributes"]['name'];
    }
    }

    $this->_cache->save($schemaFields);
    } else {
    $schemaFields = $this->_cache->load($key);
    }
    $this->_schemaFields = $schemaFields;
    return $this->_schemaFields;
    }

    /** Check if the core exists
     *
     * @return boolean
     * @throws Pas_Solr_Exception
     */
    protected function _checkCoreExists(){
    if(!in_array($this->_core,$this->_getCores())){
        throw new Pas_Solr_Exception('That is not a valid core',500);
    } else {
        return true;
    }
    }

    /** Set the solr configuration to use
     *
     * @param type $core
     * @return type
     */
    protected function _setSolrConfig($core){
    $config = $this->_config->solr->toArray();
    if(isset($core)){
    	$config['core'] = $core;
    }
    return $this->_solrConfig = array('adapteroptions' => $config);
    }

    /** Get the user's role
     *
     * @return string
     */
    protected function _getRole(){
	$user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    	return $person->role;
    } else {
    	return false;
    }
    }

    /** Set the fields to return
     *
     * @param array $fields
     * @return type
     */
    public function setFields($fields = NULL){
    if(is_array($fields)){
        $this->_fields = $fields;
    } else {
       $this->_fields = array('*');
    }
    return $this->_fields;
    }

    /** Set the parameters to use
     *
     * @param array $params
     * @return type
     */
    public function setParams(array $params){
    	if(is_array($params)){
            $this->_params = $params;
    	return $this->_params;
    	}
    }

    /** Set fields to highlight
     *
     * @param array $highlights
     * @return type
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

    /** Create the filter queries
     *
     * @param array $params
     * @throws Pas_Solr_Exception
     */
    protected function _createFilters(array $params){
    if(is_array($params)){
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

    /** Set the facets up
     *
     * @param type $facets
     * @return type
     */
    public function setFacets($facets){
    	if(is_array($facets)){
                $this->_setFacetFieldsAvailable($facets);
    		$this->_facets = $facets;
    		return $this->_facets;
    	}
    }

    public function getNumber(){
    return $this->_resultset->getNumFound();
    }
    /** Create the paginator
     *
     * @return object
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
    return $data;
    }

    /** Process the facets
     *
     * @return boolean
     */
    public function _processFacets(){
        if($this->_facets){
        $facetData = array();
        foreach($this->_facets as $k){
            $facetData[$k] = array();
            $f = $this->_resultset->getFacetSet()->getFacet($k);
            foreach($f as $value => $count) {

            $facetData[$k][ $value ]  = $count;
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

    foreach($fields as $f){
        if(!in_array($f,$this->_schemaFields)){
            throw new Pas_Solr_Exception('The field ' . $f
                    . ' is not in the schema');
        }
    }
    } else {
        throw new Pas_Solr_Exception('The fields supplied are not an array');
    }
    }

    /** Set the sort field and direction
     * @access protected
     * @param string $core
     * @param array $params
     * @return array
     * @throws Pas_Solr_Exception
     */
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
                    throw new Pas_Solr_Exception('That directional sort does not exist');
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
    if(isset($params['show'])){
        $rows = $params['show'];
        if($rows > 5000){
            $rows = 50;
        }
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
    return $start;
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
    $select['rows'] = $this->_getRows($this->_params);
//    $this->_getRows($this->_params);
    $select['start'] = $this->_getStart($this->_params);

    if(array_key_exists('format', $this->_params)){
    $this->_processFormats($this->_params);
    }

    if(array_key_exists('q',$this->_params)){
    $select['query'] = $this->_params['q'];
            unset($this->_params['q']);
    }

//	$customizer = $this->_solr->getPlugin('customizerequest');
////	$customizer->createCustomization('transform')
////           ->setType('param')
////           ->setName('tr')
////           ->setValue('example.xsl');
//	$customizer->createCustomization('format')
//           ->setType('param')
//           ->setName('wt')
//           ->setValue($this->_format);
//    // get a select query instance based on the config
    $this->_query = $this->_solr->createSelect($select);



    if(!in_array($this->_getRole(), $this->_allowed) || is_null($this->_getRole()) ) {
    if(array_key_exists('workflow', array_flip($this->_schemaFields))){
    $this->_query->createFilterQuery('workflow')->setQuery('workflow:[3 TO 4]');
    }
    if(array_key_exists('parish', $this->_params) && ($this->_core === 'beowulf')){
    $this->_query->createFilterQuery('knownas')->setQuery('knownas:["" TO *]');
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

    $data = $this->_solr->select($this->_query);
    return $data->getResponse()->getBody();

    }

    protected function _buildFacetQueries($k, $v){
        return $this->_query->createFilterQuery($k)->setQuery(substr($k, 2) . ':"' . $v . '"');
    }

    public function debugQuery(){
    Zend_Debug::dump($this->_params,'The params sent');
    Zend_Debug::dump($this->_query, 'The Query!');
    Zend_Debug::dump($this->_fields, 'The field list');
    Zend_Debug::dump($this->_schemaFields, 'The schema fields');
    }

    public function debugProcessing(){
    Zend_Debug::dump($this->_createPagination($this->_resultset), 'The pagination');
    Zend_Debug::dump($this->_processResults($this->_resultset), 'The processed results');
    Zend_Debug::dump($this->_processFacets($this->_resultset, $this->_facets),'The facet set');
    }

    /** Create the facets
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

	public function getFields(){
		return $this->_fields;
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
            return $this->_format = 'json';
        }
    }

    public function getHeader(){
    	switch($this->_format){
    		case 'csv':
    			$header = 'application/csv';
    			break;
    		case 'xml':
    			$header = 'text/xml';
    			break;
    		case 'json':
    			$header = 'application/json';
    			break;
    		case 'atom':
    			$header = 'application/atom+xml';
    			break;
    		case 'rss':
    			$header = 'application/rss+xml';
    			break;
    		case 'rdf':
    			$header = 'application/rdf+xml';
    		case 'n3':
				$header = 'text/n3';
    			break;
    		case 'turtle':
    			$header =  'text/turtle';
    			break;
    		case 'midas':
    			$header = 'text/xml';
    			break;
    		case 'kml':
    			$header = 'application/vnd.google-earth.kml+xml';
    			break;
    		default:
    			$header = 'text/html';
    			break;
	    	}
	return $header;
    }
}

