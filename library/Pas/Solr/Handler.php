<?php

/**
 * Solr handler class for retrieving data from the solr indexes
 * An example of use:
 * <code>
 * <?php
 * $search = new Pas_Solr_Handler();
 * $search->setCore($core);
 * $search->setFields($fields);
 * $search->setParams($params);
 * $search->execute();
 * </code>
 *
 * @author        Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category      Pas
 * @package       Solr
 * @subpackage    Handler
 * @uses          Pas_Solr_Exception
 * @uses          Solarium_Client
 * @uses          Pas_Solr_SensitiveFields
 * @license       http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example       /app/modules/database/controllers/SearchController.php
 */
class Pas_Solr_Handler
{
    /** The config
     *
     * @access protected
     * @var \Zend_Config
     */
    protected $_config;

    /** The default location for the schema file
     *
     * @access protected
     * @var string
     */
    protected $_schemaFile;

    /** The schema path
     *
     * @access protected
     * @var string
     */
    protected $_schemaPath;

    /** The cache object
     *
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The solr config object
     *
     * @access protected
     * @var type
     */
    protected $_solrConfig;

    /** The default core
     *
     * @access protected
     * @var string
     * @todo   change option when we rename cores
     */
    protected $_core = 'objects';

    /** The solr object
     *
     * @access protected
     * @var \Solarium_Client
     */
    protected $_solr;

    /** The formats available for output
     *
     * @access protected
     * @var array
     */
    protected $_formats = array(
        'json',
        'csv',
        'xml',
        'midas',
        'rdf',
        'n3',
        'rss',
        'atom',
        'kml',
        'pdf',
        'geojson',
        'sitemap',
        null
    );

    /** The array of allowed higher level access
     *
     * @access protected
     * @var array
     */
    protected $_allowed = array(
        'fa',
        'flos',
        'hero',
        'hoard',
        'admin',
        'treasure',
        'research'
    );

    /** The default map option
     *
     * @access protected
     * @var boolean
     */
    protected $_map = false;

    /** The load balancer plugin
     *
     * @access protected
     * @var object
     */
    protected $_loadbalancer;

    /** The default set of fields to query
     *
     * @access protected
     * @var array
     */
    protected $_fields = array('*');

    /** The array of fields in a schema
     *
     * @access protected
     * @var array
     */
    protected $_schemaFields = array();

    /** The array of cores in the system
     *
     * @access protected
     * @var array
     */
    protected $_cores = array();

    /** The array of parameters to query
     *
     * @access protected
     * @var array
     */
    protected $_params = array();

    /** The array of fields to highlight
     *
     * @access protected
     * @var array
     */
    protected $_highlights = array();

    /** The start value for the query to run from
     *
     * @access protected
     * @var int
     */
    protected $_start = 0;

    /** The start page
     *
     * @access protected
     * @var int
     */
    protected $_page = 1;

    /** Set the maximum rows to return
     *
     * @access protected
     * @var int
     */
    protected $_rows = 20;

    /** The format to parse
     *
     * @access protected
     * @var string
     */
    protected $_format = 'json';

    /** Boolean field for processing stats
     *
     * @access protected
     * @var boolean
     */
    protected $_stats = false;

    /** The default field for stats to be produced
     *
     * @access protected
     * @var array
     */
    protected $_statsFields = array('quantity');

    /** The default direction of sort
     *
     * @access protected
     * @var array
     */
    protected $_sort = array('created' => 'desc');

    /** The facets array
     *
     * @access protected
     * @var array
     */
    protected $_facets = array();

    protected $_myfinds = false;

    /** Boolean field for processing knownas
     *
     * @access protected
     * @var boolean
     */
    protected $_haveUsedKnownAsFilter = false;

    /**
     * @return null
     */
    public function getMyfinds()
    {
        return $this->_myfinds;
    }

    /**
     * @param null $myfinds
     */
    public function setMyfinds($myfinds)
    {
        $this->_myfinds = $myfinds;
        return $this;
    }


    /** Get the sort array
     *
     * @access public
     * @return array
     * @throws Pas_Solr_Exception
     */
    public function getSort()
    {
        $params = $this->getParams();
        if (array_key_exists('sort', $params)) {
            $this->checkFieldList(array($params['sort']));
            $field = $params['sort'];
        } else {
            $field = 'created';
        }
        $allowed = array('desc', 'asc');
        if (array_key_exists('direction', $params)) {
            if (in_array($params['direction'], $allowed)) {
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

    /** The query to send to solr
     *
     * @access protected
     * @var object
     */
    protected $_query;

    protected $_facetFields;

    protected $_facetSet;

    /** Process format key
     *
     * @access protected
     * @return boolean
     */
    public function getFormat()
    {
        $params = $this->getParams();
        if (array_key_exists('format', $params)) {
            $format = $params['format'];
            if (in_array($format, $this->getFormats())) {
                $this->_format = $format;
            }
        }
        return $this->_format;
    }

    /** Get the starting page
     *
     * @access public
     * @return int
     */
    public function getPage()
    {
        $params = $this->getParams();
        if (array_key_exists('page', $params)) {
            $this->_page = $params['page'];
        }
        return $this->_page;
    }

    /** Get the starting numbef for the query
     *
     * @access public
     * @return int
     */
    public function getStart()
    {
        $params = $this->getParams();
        if (array_key_exists('page', $params) && !is_null($params['page'])) {
            $this->_start = abs(($params['page'] - 1) * $this->getRows($params));
        }
        return $this->_start;
    }

    /** Get the rows
     *
     * @access public
     * @return int
     */
    public function getRows()
    {
        $params = $this->getParams();
        $format = $this->getFormat();
        if (isset($params['show']) && in_array($format, array('json', 'xml', 'geojson', null))) {
            $show = $params['show'];
            if ($show > 100) {
                $show = 100;
            }
        } elseif ($format === 'kml') {
            if (!isset($params['show'])) {
                $show = 1200;
            } else {
                $show = $params['show'];
            }
        } elseif ($format === 'pdf') {
            $show = 500;
        } elseif ($format === 'sitemap') {
            $show = 1000;
        } else {
            $show = 20;
        }
        return $show;
    }


    /** Get the schema path
     *
     * @access public
     * @return string
     */
    public function getSchemaPath()
    {
        $this->_schemaPath = $this->getConfig()->solr->schema->path;
        return $this->_schemaPath;
    }


    /** Get the schema file
     *
     * @access public
     * @return string
     */
    public function getSchemaFile()
    {
        $this->_schemaFile = $this->getConfig()->solr->schema->file;
        return $this->_schemaFile;
    }

    /** Set the field on which to generate stats
     *
     * @access public
     * @param string $value
     * @return string
     */
    public function setStats($value)
    {
        return $this->_stats = $value;
    }

    /** Get the field on which to generate stats
     *
     * @access public
     * @return type
     */
    public function getStats()
    {
        return $this->_stats;
    }

    /** Set whether the function is producing mapping data
     *
     * @access public
     * @param string $map
     * @return \Pas_Solr_Handler
     */
    public function setMap($map)
    {
        $this->_map = $map;
        return $this;
    }

    /** Get whether map is true or false
     *
     * @access public
     * @return boolean
     */
    public function getMap()
    {
        return $this->_map;
    }

    /** Set the field on which to get stats
     *
     * @access public
     * @param array $fields
     * @return \Pas_Solr_Handler
     */
    public function setStatsFields(array $fields)
    {
        if (is_array($fields)) {
            $this->_statsFields = $fields;
        }
        return $this;
    }

    /** Get the stats field array
     *
     * @access public
     * @return array
     */
    public function getStatsFields()
    {
        return $this->_statsFields;
    }

    /** Return the formats that can be queried
     *
     * @access public
     * @return array
     */
    public function getFormats()
    {
        return $this->_formats;
    }

    /** Get the allowed array
     *
     * @access public
     * @return array
     */
    public function getAllowed()
    {
        return $this->_allowed;
    }

    /** Get the cache object
     *
     * @access public
     * @return \Zend_Cache
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config object
     *
     * @access public
     * @return \Zend_Config
     */
    public function getConfig()
    {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the core to search
     *
     * @access public
     * @return string
     */
    public function getCore()
    {
        return $this->_core;
    }

    /** Set the core to search upon
     *
     * @access public
     * @param string $core
     * @return \Pas_Solr_Handler
     */
    public function setCore($core)
    {
        $this->_core = $core;
        return $this;
    }

    /** Get the solr config options
     *
     * @access public
     * @return array
     */
    public function getSolrConfig()
    {
        $config = $this->getConfig()->solr->master->toArray();
        $config['core'] = $this->getCore();
        return $this->_solrConfig = array('adapteroptions' => $config);
    }

    /** Get the solr object for querying cores
     *
     * @access public
     * @return \Solarium_Client
     */
    public function getSolr()
    {
        $this->_solr = new Solarium_Client($this->getSolrConfig());
        $this->_solr->setAdapter('Solarium_Client_Adapter_ZendHttp');
        $this->_solr->getAdapter()->getZendHttp();
        $loadbalancer = $this->_solr->getPlugin('loadbalancer');
        $master = $this->getConfig()->solr->master->toArray();
        $asgard = $this->getConfig()->solr->asgard->toArray();
        $valhalla = $this->getConfig()->solr->valhalla->toArray();
        $loadbalancer->addServer('objects', $master, 100);
        $loadbalancer->addServer('asgard', $asgard, 200);
        $loadbalancer->addServer('valhalla', $valhalla, 150);
        $loadbalancer->setFailoverEnabled(true);
        $this->_solr->getAdapter()->getZendHttp();
        $this->_loadbalancer = $loadbalancer;
        return $this->_solr;
    }

    /** Get the load balancer
     *
     * @access public
     * @return type
     */
    public function getLoadbalancer()
    {
        return $this->_loadbalancer;
    }

    /** Set the facet fields
     *
     * @access public
     * @return \Pas_Solr_Handler
     */
    protected function setFacetFields()
    {
        $facetFields = array();
        foreach ($this->getSchemaFields() as $k => $v) {
            $facetFields[$k] = 'fq' . $v;
        }
        $this->_facetFields = $facetFields;
        return $this;
    }

    /** Get the facet fields
     *
     * @access public
     * @return
     */
    public function getFacetFields()
    {
        $facetFields = array();
        foreach ($this->getSchemaFields() as $k => $v) {
            $facetFields[$k] = 'fq' . $v;
        }
        $this->_facetFields = $facetFields;
        return $this->_facetFields;
    }

    /** Get the cores available from directory
     *
     * @access public
     * @return array
     */
    public function getCores()
    {
        if (!($this->getCache()->test('solrCores'))) {
            $dir = new DirectoryIterator($this->getSchemaPath());
            $cores = array();
            foreach ($dir as $dirEntry) {
                if ($dirEntry->isDir() && !$dirEntry->isDot()) {
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
     *
     * @access public
     * @return array
     */
    public function getSchemaFields()
    {
        $file = $this->getSchemaPath() . $this->getCore() . $this->getSchemaFile();
        $key = md5($file);
        if (!($this->getCache()->test($key))) {
            if (file_exists($file)) {
                $xml = simplexml_load_file($file);
                $schemaFields = array();
                foreach ($xml->fields->field as $field) {
                    $string = get_object_vars($field->attributes());
                    //This bit looks honky, couldn't get it to work with object notation
                    $schemaFields[] = $string["@attributes"]['name'];
                }
            } else {
                throw new Zend_Exception('That path does not exist', 500);
            }
            $this->getCache()->save($schemaFields);
        } else {
            $schemaFields = $this->getCache()->load($key);
        }
        $this->_schemaFields = $schemaFields;
        return $this->_schemaFields;
    }

    /** Check if the core exists
     *
     * @access public
     * @return boolean
     * @throws Pas_Solr_Exception
     */
    protected function checkCoreExists()
    {
        if (!in_array($this->getCore(), $this->getCores())) {
            throw new Pas_Solr_Exception('That is not a valid core', 500);
        } else {
            return true;
        }
    }

    /** Get the user's role
     *
     * @access public
     * @return string
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        return $user->getRole();
    }

    /** Get the user's ID
     *
     * @access public
     * @return string
     */
    public function getUserID()
    {
        $user = new Pas_User_Details();
        return $user->getIdentityForForms();
    }

    /** Get the user's ID
     *
     * @access public
     * @return string
     */
    public function getPerson()
    {
        $user = new Pas_User_Details();
        return $user->getPerson();
    }

    /** Get the list of fields to query
     *
     * @access public
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /** Set the fields to return
     *
     * @access public
     * @return type
     */
    public function setFields($fields)
    {
        $this->checkFieldList($fields);
        $this->_fields = $fields;
        return $this->_fields;
    }

    /** Set the parameters to use
     *
     * @access public
     * @param array $params
     * @return array
     */
    public function setParams(array $params)
    {
        if (is_array($params)) {
            $this->_params = $this->filterParams($params);
        }
        return $this->_params;
    }

    /** Get the parameters to query
     *
     * @access public
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }


    /** Filter parameters being passed to search
     *
     * @access public
     * @param array $params
     * @return array
     */
    public function filterParams(array $params)
    {
        if (array_key_exists('created', $params)) {
            $created = $params['created'];
            $queryDateA = $created . "T00:00:00.001Z";
            $queryDateB = $created . "T23:59:59.99Z";
            $params['created'] = $queryDateA . ' TO ' . $queryDateB;
        }
        if (array_key_exists('updated', $params)) {
            $updated = $params['updated'];
            $queryDateA = $updated . "T00:00:00.001Z";
            $queryDateB = $updated . "T23:59:59.99Z";
            $params['updated'] = $queryDateA . ' TO ' . $queryDateB;
        }
        if (array_key_exists('createdBefore', $params) && array_key_exists('createdAfter', $params)) {
            $queryDateA = $params['createdAfter'] . "T00:00:00.001Z";
            $queryDateB = $params['createdBefore'] . "T23:59:59.99Z";
            $params['created'] = $queryDateA . ' TO ' . $queryDateB;
            unset($params['createdBefore']);
            unset($params['createdAfter']);
        }
        if (array_key_exists('createdBefore', $params)) {
            $queryDate = $params['createdBefore'] . "T23:59:59.99Z";
            $params['created'] = '* TO ' . $queryDate;
            unset($params['createdBefore']);
        }
        if (array_key_exists('createdAfter', $params)) {
            $queryDate = $params['createdAfter'] . "T00:00:00.001Z";
            $params['created'] = $queryDate . ' TO * ';
            unset($params['createdAfter']);
        }

        if (array_key_exists('updatedBefore', $params) && array_key_exists('updatedAfter', $params)) {
            $queryDateA = $params['updatedAfter'] . "T00:00:00.001Z";
            $queryDateB = $params['updatedBefore'] . "T23:59:59.99Z";
            $params['updated'] = $queryDateA . ' TO ' . $queryDateB;
            unset($params['updatedBefore']);
            unset($params['updatedAfter']);
        }
        if (array_key_exists('updatedBefore', $params)) {
            $queryDate = $params['updatedBefore'] . "T23:59:59.99Z";
            $params['updated'] = '* TO ' . $queryDate;
            unset($params['updatedBefore']);
        }
        if (array_key_exists('updatedAfter', $params)) {
            $queryDate = $params['updatedAfter'] . "T00:00:00.001Z";
            $params['updated'] = $queryDate . ' TO NOW ';
            unset($params['updatedAfter']);
        }
        foreach ($params as $k => $v) {
            if (is_null($v) || ($v === '')) {
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
    public function setHighlights(array $highlights)
    {
        if (is_array($highlights)) {
            $this->_highlights = $highlights;
        }
        return $this->_highlights;
    }

    /** Create highlighting
     *
     * @access public
     * @return array
     */
    public function createHighlighting()
    {
        $hl = $this->_query->getHighlighting();
        $hl->setFields(implode($this->_highlights, ','));
        $hl->setSimplePrefix('<span class="hl">');
        $hl->setSimplePostfix('</span>');
        return $hl;
    }

    /** Get the highlights back
     *
     * @access public
     * @return type
     */
    public function getHighlights()
    {
        if ($this->_highlights) {
            return $this->_resultset->getHighlighting();
        }
    }

    /** Create filters
     *
     * @access protected
     * @param array $params
     * @throws Pas_Solr_Exception
     */
    protected function _createFilters(array $params)
    {
        if (is_array($params)) {
            if (
                array_key_exists('d', $params) && array_key_exists('lon', $params) && array_key_exists(
                    'lat',
                    $params
                )
            ) {
                if (!is_null($params['d']) && !is_null($params['lon']) && !is_null($params['lat'])) {
                    $helper = $this->_query->getHelper();
                    $this->_query->createFilterQuery('geo')->setQuery(
                        $helper->geofilt(
                            $params['lat'],
                            $params['lon'],
                            'coordinates',
                            $params['d']
                        )
                    );
                }
            }
            $map = $this->getMap();
            if (
                ($map === true) && !in_array(
                    $this->getRole(),
                    $this->getAllowed()
                ) && ($this->getCore() === 'objects')
            ) {
                $this->_query->createFilterQuery('hascoords')->setQuery('gridref:["" TO *]');
                $this->setKnownAsFilterOnce();
            } elseif ($map === true && ($this->getCore() === 'objects')) {
                $this->_query->createFilterQuery('hascoords')->setQuery('gridref:["" TO *]');
            }
            if (array_key_exists('bbox', $params)) {
                $coords = new Pas_Solr_BoundingBoxCheck($params['bbox']);
                $bbox = $coords->checkCoordinates();
                $this->_query->createFilterQuery('bbox')->setQuery($bbox);
            }
            foreach ($params as $key => $value) {
                if (!in_array($key, $this->_schemaFields)) {
                    unset($params[$key]);
                }
            }
            if (isset($params['thumbnail'])) {
                $this->_query->createFilterQuery('thumbnails')->setQuery('thumbnail:[1 TO *]');
                unset($params['thumbnail']);
            }

            if (isset($params['3D'])) {
                $this->_query->createFilterQuery('3dcontent')->setQuery('3D:[* TO *]');
                unset($params['3D']);
            }

            $this->checkFieldList(array_keys($params));
            foreach ($params as $key => $value) {
                $this->_query->createFilterQuery($key . $value)->setQuery($key . ':"' . $value . '"');
            }
        } else {
            throw new Pas_Solr_Exception('The search params must be an array');
        }
    }

    /** Set the facets array up
     *
     * @access public
     * @param array $facets
     * @return \Pas_Solr_Handler
     */
    public function setFacets(array $facets)
    {
        if (is_array($facets)) {
            $this->setFacetFields($facets);
            $this->_facets = $facets;
        }
        return $this;
    }

    /** Get the facets that have been sent
     *
     * @access public
     * @return array
     */
    public function getFacets()
    {
        return $this->_facets;
    }

    /** Get the number of results from a result set
     *
     * @access public
     * @return int
     */
    public function getNumber()
    {
        return $this->_resultset->getNumFound();
    }

    /** Create a pagination object
     *
     * @access public
     * @return type
     */
    public function createPagination()
    {
        $paginator = Zend_Paginator::factory($this->getNumber());
        $paginator->setCurrentPageNumber($this->getPage())
            ->setItemCountPerPage($this->getRows())
            ->setPageRange(10);
        return $paginator;
    }

    /** Process the results of the query
     *
     * @access public
     * @return array $data
     */
    public function processResults()
    {
        $data = array();
        foreach ($this->_resultset as $doc) {
            $fields = array();
            foreach ($doc as $key => $value) {
                $fields[$key] = $value;
            }
            $data[] = $fields;
        }
        if ($this->getFormat() != 'kml') {
            $processor = new Pas_Solr_SensitiveFields();
            $clean = $processor->cleanData($data, $this->getRole(), $this->_core);
        } else {
            $clean = $data;
        }
        $return = array();
        foreach ($clean as $d) {
            if (array_key_exists('_version_', $d)) {
                unset($d['_version_']);
            }
            $return[] = $d;
        }
        return $return;
    }

    /** Process stats for a query
     *
     * @access public
     * @return array
     */
    public function processStats()
    {
        $stats = $this->_resultset->getStats();

        if (is_object($stats)) {
            foreach ($stats as $stat) {
                $data = array(
                    'stdDeviation' => $stat->getStddev(),
                    'mean' => $stat->getMean(),
                    'sum' => $stat->getSum(),
                    'query' => $stat->getName(),
                    'minima' => $stat->getMin(),
                    'maxima' => $stat->getMax(),
                    'count' => $stat->getCount(),
                    'missing' => $stat->getMissing(),
                    'sumOfSquares' => $stat->getSumOfSquares(),
                    'mean' => $stat->getMean()
                );
            }
            return $data;
        } else {
            return $data = array();
        }
    }

    /** Process facets for display
     *
     * @access public
     * @return boolean
     */
    public function processFacets()
    {
        $facets = $this->getFacets();
        if ($facets) {
            $facetData = array();
            foreach ($facets as $k) {
                $facetData[$k] = array();
                $facet = $this->_resultset->getFacetSet()->getFacet($k);
                if ($facet) {
                    foreach ($facet as $value => $count) {
                        $facetData[$k][$value] = $count;
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
     * @access protected
     * @param string $fields
     * @return \Pas_Solr_Handler
     * @throws Pas_Solr_Exception
     */
    protected function checkFieldList($fields)
    {
        if (is_null($fields)) {
            $schemaFields = $this->getSchemaFields();
            $schemaFields[] = '*';
            $schemaFields[] = 'q';
            foreach ($fields as $field) {
                if (!in_array($field, array_flip($schemaFields))) {
                    $message = 'The field ' . $field . ' is not in the schema';
                    throw new Pas_Solr_Exception($message, 500);
                }
            }
        }
        return $this;
    }

    /**
     * @param $userRole
     * @param ...$roles
     * @return bool
     */
    protected function checkRoleAllowed($userRole, ...$removedRoles): bool
    {
        $allowed = $this->getAllowed();
        if (!empty($removedRoles)) {
            $allowed = array_diff($allowed, $removedRoles);
        }

        return in_array($userRole, $allowed);
    }

    /** Execute the query
     *
     * @access public
     * @return object
     */
    public function execute()
    {
        // create a ping query
        $ping = $this->getSolr()->createPing();

        try {
            $this->getSolr()->ping($ping);
        } catch (Solarium_Exception $e) {
        }
        $params = $this->getParams();
        $select = array(
            'query' => '*:*',
            //'fields'        => array('*'),
            'filterquery' => array(),
        );
        $select['fields'] = $this->getFields();
        $select['sort'] = $this->getSort();
        $select['start'] = $this->getStart();
        if (array_key_exists('format', $params)) {
            $this->getFormats($params);
        }
        $select['rows'] = $this->getRows();
        if (array_key_exists('q', $params)) {
            $select['query'] = $params['q'];
            unset($params['q']);
        }

        // get a select query instance based on the config
        $this->_query = $this->getSolr()->createSelect($select);

        if (array_key_exists('created', $params)) {
            $this->_query->createFilterQuery('created')->setQuery('created:[' . $params['created'] . ']');
            unset($params['created']);
        }
        if (array_key_exists('updated', $params)) {
            $this->_query->createFilterQuery('updated')->setQuery('updated:[' . $params['updated'] . ']');
            unset($params['updated']);
        }
        if (array_key_exists('todate', $params) && array_key_exists('fromdate', $params)) {
            $this->_query->createFilterQuery('range')
                ->setQuery(
                    'todate:['
                    . $params['fromdate']
                    . ' TO ' . $params['todate'] . ']'
                );
            $this->_query->createFilterQuery('rangedate')
                ->setQuery(
                    'fromdate:['
                    . $params['fromdate']
                    . ' TO '
                    . $params['todate'] . ']'
                );
            unset($params['todate']);
            unset($params['fromdate']);
        }
        if (array_key_exists('fromdate', $params)) {
            $this->_query->createFilterQuery('datefrom')
                ->setQuery(
                    'fromdate:['
                    . $params['fromdate']
                    . ' TO * ]'
                );
            unset($params['fromdate']);
        }
        if (array_key_exists('todate', $params)) {
            $this->_query->createFilterQuery('todate')
                ->setQuery(
                    'todate:[* TO '
                    . $params['todate']
                    . ']'
                );
            unset($params['todate']);
        }
        //Statistics are only enabled in this instance for the finds index
        if ($this->getCore() === 'objects') {
            $stats = $this->_query->getStats();
            foreach ($this->getStatsFields() as $field) {
                $stats->createField($field);
            }
        }

        if ($this->checkRoleAllowed($this->getRole(), "research") == false) {
            if (($this->getRole() == 'member' || $this->getRole() == 'research') && $this->getMyfinds()) {
                $this->_query->createFilterQuery('myfinds')->setQuery('createdBy:' . $params['createdBy']);
            } elseif (array_key_exists('workflow', array_flip($this->getSchemaFields()))) {
                    $query = "workflow:[3 TO 4] OR createdBy:" . $this->getUserID();
                    $person = $this->getPerson();
                if (
                        $person !== false && property_exists($person, 'peopleID')
                        && !is_null($person->peopleID)
                        && $this->getCore() !== 'images'
                ) {
                    $query .= " OR recorderID:" . $person->peopleID;
                }
                    $this->_query->createFilterQuery('workflow')->setQuery($query);
            }
        }

        if ($this->checkRoleAllowed($this->getRole()) == false) {
            if (
                (array_key_exists('parish', $params)
                    || array_key_exists('fourFigure', $params) || array_key_exists('parishID', $params))
                && ($this->getCore() === 'objects')
            ) {
                $this->setKnownAsFilterOnce();
            }
            if ($this->getFormat() === 'kml' && ($this->getCore() === 'objects')) {
                $this->_query->createFilterQuery('geopresent')->setQuery('gridref:[* TO *]');
                $this->setKnownAsFilterOnce();
            }
        }
        if (!is_null($this->getFacets())) {
            $this->_createFacets($this->getFacets());
            foreach ($params as $k => $v) {
                if (in_array($k, $this->getFacetFields())) {
                    $this->buildFacetQueries($k, $v);
                    unset($params['k']);
                }
            }
        }
        $this->_createFilters($params);
        $this->_resultset = $this->getSolr()->select($this->_query);
        return $this->_resultset;
    }

    /** Create a facet query based on the key value pairs of an array
     *
     * @access public
     * @param string $k
     * @param string $v
     */
    public function buildFacetQueries($k, $v)
    {
        return $this->_query->createFilterQuery($k)->setQuery(
            substr($k, 2)
            . ':"' . $v . '"'
        );
    }

    /** Debug a query
     *
     * @access public
     * @return \Pas_Solr_Handler
     */
    public function debugQuery()
    {
        Zend_Debug::dump($this->getParams(), 'The params sent');
        Zend_Debug::dump($this->_query, 'The Query');
        Zend_Debug::dump($this->getFields(), 'The field list');
        Zend_Debug::dump($this->getSchemaFields(), 'The schema fields');
        Zend_Debug::dump($this->getFormat(), 'The format called');
        return $this;
    }

    /** Debug processing of a query
     *
     * @access public
     * @return \Pas_Solr_Handler
     */
    public function debugProcessing()
    {
        Zend_Debug::dump($this->createPagination($this->_resultset), 'Pagination');
        Zend_Debug::dump($this->processResults($this->_resultset), 'Processed results');
        Zend_Debug::dump($this->processFacets(), 'The facet set');
        Zend_Debug::dump($this->processStats(), 'Statistics');
        Zend_Debug::dump($this->getLoadBalancerKey(), 'The load balancer key');
        return $this;
    }

    /** Create the facets
     *
     * @access protected
     * @return \Pas_Solr_Handler
     */
    protected function _createFacets()
    {
        $this->checkFieldList($this->getFacets());
        $facetSet = $this->_query->getFacetSet();
        $facetSet->setMinCount(1);
        $facetSet->setLimit(-1);
        $facetSet->setSort('count');
        foreach ($this->getFacets() as $key) {
            $facetSet->createFacetField($key)->setField($key);
        }
        return $this;
    }

    /**
     * Check if the data is filtered for the KnownAs, if not then filter it
     */
    public function getLoadBalancerKey()
    {
        return $this->getLoadbalancer()->getLastServerKey();
    }

    public function setKnownAsFilterOnce()
    {
        if (!($this->_haveUsedKnownAsFilter)) {
            $this->_query->createFilterQuery('knownas')->setQuery('-knownas:["" TO *]');
            $this->_haveUsedKnownAsFilter = true;
        }
    }
}
