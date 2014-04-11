<?php
/** Modified in places for the creation of item data via solr
 *
 * @package OaiPmhRepository
 * @subpackage Libraries
 * @author John Flatness, Yu-Hsun Lin & Daniel Pett
 * @copyright Copyright 2009 John Flatness, Yu-Hsun Lin
 * @copyright Daniel Pett 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * OaiPmhRepository_ResponseGenerator generates the XML responses to OAI-PMH
 * requests recieved by the repository.  The DOM extension is used to generate
 * all the XML output on-the-fly.
 *
 * @package OaiPmhRepository
 * @subpackage Libraries
 */
class Pas_OaiPmhRepository_ResponseGenerator extends Pas_OaiPmhRepository_OaiXmlGeneratorAbstract
{

    /** The base url for requests
     *
     */
    const OAI_PMH_BASE_URL = '/database/oai/request/';

    /**
     * HTTP query string or POST vars formatted as an associative array.
     * @var array
     */
    private $query;

    /**
     * Array of all supported metadata formats.
     * $metdataFormats['metadataPrefix'] = ImplementingClassName
     * @var array
     */
    private $metadataFormats;

    /** The metadata classes
     *
     * @var type
     */
    private $metadataFormatsClasses;

    /**
     * Constructor
     *
     * Creates the DomDocument object, and adds XML elements common to all
     * OAI-PMH responses.  Dispatches control to appropriate verb, if any.
     *
     * @param array $query HTTP POST/GET query key-value pair array.
     * @uses dispatchRequest()
     */
    public function __construct($query) {

    /** set up the server Url
     *
     */
    $url = new Pas_OaiPmhRepository_ServerUrl();
    $this->_serverUrl = $url->get();

    $this->_error = false;

    $this->query = $query;

    $this->document = new DomDocument('1.0', 'UTF-8');

    //formatOutput makes DOM output "pretty" XML.  Good for debugging, but
    //adds some overhead, especially on large outputs.
    $this->document->formatOutput = true;
    $this->document->xmlStandalone = true;
    $root = $this->document->createElementNS(self::OAI_PMH_NAMESPACE_URI, 'OAI-PMH');

    $this->document->appendChild($root);

    $root->setAttributeNS(self::XML_SCHEMA_NAMESPACE_URI, 'xsi:schemaLocation',
            self::OAI_PMH_NAMESPACE_URI.' '.self::OAI_PMH_SCHEMA_URI);
    $responseDate = $this->document->createElement('responseDate', self::unixToUtc(time()));

    $root->appendChild($responseDate);

    $this->metadataFormats = $this->getFormats();

    $this->dispatchRequest();
    }

    /**
     * Parses the HTTP query and dispatches to the correct verb handler.
     *
     * Checks arguments for each verb type, and sets XML request tag.
     *
     * @uses checkArguments()
     */
    private function dispatchRequest() {
    $request = $this->document->createElement('request',  $this->_serverUrl . self::OAI_PMH_BASE_URL);
    $this->document->documentElement->appendChild($request);
    $requiredArgs = array();
    $optionalArgs = array();

    if(isset($this->query['resumptionToken'])){
    $resumptionToken = $this->query['resumptionToken'];
    }
    if(isset($this->query['verb'])){
    $verb = $this->query['verb'];
    } else {
    $verb = NULL;
    $this->throwError(self::OAI_ERR_BAD_VERB);

    }
    if(isset($resumptionToken)){
    $requiredArgs = array('resumptionToken');
    } else {
    switch($verb)  {
		case 'Identify':
			break;
		case 'GetRecord':
		$requiredArgs = array('identifier', 'metadataPrefix');
			break;
		case 'ListRecords':
		$requiredArgs = array('metadataPrefix');
		$optionalArgs = array('from', 'until', 'set');
			break;
		case 'ListIdentifiers':
		$requiredArgs = array('metadataPrefix');
		$optionalArgs = array('from', 'until', 'set');
			break;
		case 'ListSets':
			break;
		case 'ListMetadataFormats':
		$optionalArgs = array('identifier');
        	break;
		default:
		$this->throwError(self::OAI_ERR_BAD_VERB);
            }
    }
    $this->checkArguments($requiredArgs, $optionalArgs);

        if(!$this->_error) {
            foreach($this->query as $key => $value)
                $request->setAttribute($key, $value);

            if(isset($this->query['resumptionToken']))
                $this->resumeListResponse($resumptionToken);
            /* ListRecords and ListIdentifiers use a common code base and share
               all possible arguments, and are handled by one function. */
            else if($verb == 'ListRecords' || $verb == 'ListIdentifiers')
                $this->initListResponse();
            else {
                /* This Inflector use means verb-implementing functions must be
                   the lowerCamelCased version of the verb name. */
    if ( false === function_exists('lcfirst') ){
    function lcfirst( $str ) {
    	return (string)(strtolower(substr($str,0,1)).substr($str,1));
    }
    }
                $functionName = lcfirst($verb);
                $this->$functionName();

            }
        }
    }


    /**
     * Checks the argument list from the POST/GET query.
     *
     * Checks if the required arguments are present, and no invalid extra
     * arguments are present.  All valid arguments must be in either the
     * required or optional array.
     *
     * @param array requiredArgs Array of required argument names.
     * @param array optionalArgs Array of optional, but valid argument names.
     */
      private function checkArguments($requiredArgs = array(), $optionalArgs = array())
    {
        $requiredArgs[] = 'verb';

        if($_SERVER['REQUEST_METHOD'] == 'GET' &&
        (urldecode($_SERVER['QUERY_STRING']) != urldecode(http_build_query($this->query)))) {
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Duplicate arguments in request.");
        }

        $keys = array_keys($this->query);

        foreach(array_diff($requiredArgs, $keys) as $arg) {
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Missing required argument $arg.");
        }
        foreach(array_diff($keys, $requiredArgs, $optionalArgs) as $arg) {
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Unknown argument $arg.");

        }

        if(isset($this->query['from'])) {
        $from = $this->query['from'];
        $fromGran = self::getGranularity($from);
        if($from && !$fromGran) {
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Invalid date/time argument.");
            }
        }
        if(isset($this->query['until'])){
        $until = $this->query['until'];
        $untilGran = self::getGranularity($until);
        if($until && !$untilGran) {
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Invalid date/time argument.");
            }
        }



        if(isset($this->query['from']) && isset($this->query['until'])) {

        if($from && $until && $fromGran != $untilGran) {
            $this->throwError(self::OAI_ERR_BAD_ARGUMENT, "Date/time arguments of differing granularity.");
            }
        }
        if(isset($this->query['metadataPrefix'])) {
        $metadataPrefix = $this->query['metadataPrefix'];

        if(!array_key_exists($metadataPrefix, $this->metadataFormats)) {
            $this->throwError(self::OAI_ERR_CANNOT_DISSEMINATE_FORMAT);
        }
        }

    }



    /**
     * Responds to the Identify verb.
     *
     * Appends the Identify element for the repository to the response.
     */
    public function identify() {

    if($this->_error){
    return;
    }

    /* according to the schema, this order of elements is required for the
        response to validate */
    $elements = array(
        'repositoryName'    => self::REPOSITORY,
        'baseURL'           => $this->_serverUrl .  self::OAI_PMH_BASE_URL,
        'protocolVersion'   => self::OAI_PMH_PROTOCOL_VERSION,
        'adminEmail'        => 'info@finds.org.uk',
        'earliestDatestamp' => self::unixToUtc(0),
        'deletedRecord'     => 'no',
        'granularity'       => self::OAI_GRANULARITY_STRING,
        'compression'       => 'gzip'
    );
    $identify = $this->createElementWithChildren(
        $this->document->documentElement, 'Identify', $elements);

    $description = $this->document->createElement('description');
    $identify->appendChild($description);

    Pas_OaiPmhRepository_OaiIdentifier::describeIdentifier($description);
    }

    /**
     * Responds to the GetRecord verb.
     *
     * Outputs the header and metadata in the specified format for the specified
     * identifier.
     */
    private function getRecord()
    {
        $identifier = $this->query['identifier'];
        $metadataPrefix = $this->query['metadataPrefix'];

        $findItem = new Pas_OaiPmhRepository_OaiIdentifier();
        $itemId = $findItem->oaiIdToItem($identifier);

        if(!$itemId && !is_int($itemId)) {
            $this->throwError(self::OAI_ERR_ID_DOES_NOT_EXIST);

        }
        $solr = new Pas_OaiPmhRepository_SolrResponse();
        $item = $solr->getRecord($itemId);
        $single = $item['finds'];

        if(!$single) {
            $this->throwError(self::OAI_ERR_ID_DOES_NOT_EXIST);

        }

        if(!$this->_error) {
            $getRecord = $this->document->createElement('GetRecord');
            $this->document->documentElement->appendChild($getRecord);
            $record = new $this->metadataFormats[$metadataPrefix]($single['0'], $getRecord);
            $record->appendRecord();
        }
    }

    /**
     * Responds to the ListMetadataFormats verb.
     *
     * Outputs records for all of the items in the database in the specified
     * metadata format.
     *
     * @todo extend for additional metadata formats
     */
    private function listMetadataFormats()
    {
    	if(isset($this->query['identifier'])){
    	$identifier = $this->query['identifier'];
        $findItem = new Pas_OaiPmhRepository_OaiIdentifier();
        $itemId = $findItem->oaiIdToItem($identifier);

        if(!$itemId && !is_int($itemId)) {
            $this->throwError(self::OAI_ERR_ID_DOES_NOT_EXIST);


        }
        $solr = new Pas_OaiPmhRepository_SolrResponse();
        $item = $solr->getRecord($itemId);

        if(!$item['finds'][0]) {
            $this->throwError(self::OAI_ERR_ID_DOES_NOT_EXIST);

        }
    		}
        if(!$this->_error) {
            $listMetadataFormats = $this->document->createElement('ListMetadataFormats');
            $this->document->documentElement->appendChild($listMetadataFormats);
            foreach($this->metadataFormats as $format) {
            	$method = $format;
                $formatObject = new $method(null, $listMetadataFormats);
                $formatObject->declareMetadataFormat();
            }
        }

    }

    /**
     * Responds to the ListSets verb.
     *
     * Outputs setSpec and setName for all OAI-PMH sets (Omeka collections).
     *
     *
     */
    private function listSets()
    {
        $collectionlist = new Institutions();
        $collections = $collectionlist->listCollections();
        if(count($collections) === 0)
            $this->throwError(self::OAI_ERR_NO_SET_HIERARCHY);

        $listSets = $this->document->createElement('ListSets');

        if(!$this->_error) {
            $this->document->documentElement->appendChild($listSets);
            foreach ($collections as $collection) {
                $elements = array(
                    'setSpec' => $collection['id'],
                    'setName' => $collection['name']
                    );
                $this->createElementWithChildren($listSets, 'set', $elements);
            }
        }
    }

    /**
     * Responds to the ListIdentifiers and ListRecords verbs.
     *
     * Only called for the initial request in the case of multiple incomplete
     * list responses
     *
     * @uses listResponse()
     */
    private function initListResponse()
    {


        if(isset($this->query['from'])) {
        	$from = $this->query['from'];
            $fromDate = self::utcToDb($from);
        } else {
        	$fromDate = NULL;
        }
        if(isset($this->query['until'])) {
        	$until = $this->query['until'];
            $untilDate = self::utcToDb($until);
        } else {
        	$untilDate = NULL;
        }
    	if(isset($this->query['set'])) {
        	$set = $this->query['set'];
        } else {
        	$set = NULL;
        }
        $this->listResponse($this->query['verb'],
                            $this->query['metadataPrefix'],
                            0,
                            $set,
                            $fromDate,
                            $untilDate);
    }

    /**
     * Returns the next incomplete list response based on the given resumption
     * token.
     *
     * @param string $token Resumption token
     * @uses listResponse()
     */
    private function resumeListResponse($token)
    {

        $tokenTable = new OaiPmhRepositoryTokenTable();
        $where = array();
        $dateTime = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
        $where = $tokenTable->getAdapter()->quoteInto('expiration <= ?', $dateTime);
        $tokenTable->delete($where);

        $tokenObject = $tokenTable->getToken($token);


        if(is_null($tokenObject) || ($tokenObject['verb'] != $this->query['verb'])) {
            $this->throwError(self::OAI_ERR_BAD_RESUMPTION_TOKEN);
        } else {
            $this->listResponse($tokenObject['verb'],
                                $tokenObject['metadata_prefix'],
                                $tokenObject['cursor'],
                                $tokenObject['set'],
                                $tokenObject['from'],
                                $tokenObject['until']
                    );
        }
    }

    /**
     * Responds to the two main List verbs, includes resumption and limiting.
     * @access private
     * @param string $verb OAI-PMH verb for the request
     * @param string $metadataPrefix Metadata prefix
     * @param int $cursor Offset in response to begin output at
     * @param mixed $set Optional set argument
     * @param string $from Optional from date argument
     * @param string $until Optional until date argument
     * @uses createResumptionToken()
     */
    private function listResponse($verb, $metadataPrefix, $cursor, $set, $from, $until){

        $listLimit = 30;
        $solr = new Pas_OaiPmhRepository_SolrResponse();
        $items = $solr->getRecords($cursor, $set, $from, $until);

        $rows = $items['numberFound'];

        if($rows === 0) {
            $this->throwError(self::OAI_ERR_NO_RECORDS_MATCH, 'No records match the given criteria');

        } else {
        if($verb == 'ListIdentifiers') {
        $method = 'appendHeader';
        } else if($verb == 'ListRecords') {
        $method = 'appendRecord';
        }
        $verbElement = $this->document->createElement($verb);
        $this->document->documentElement->appendChild($verbElement);


        foreach($items['finds'] as $item) {
        $record = new $this->metadataFormats[$metadataPrefix]($item, $verbElement);
        $record->$method();
        }

            $total = $cursor + $listLimit;
           if($rows > $total) {
            $token = $this->createResumptionToken($verb, $metadataPrefix, $cursor, $set, $from, $until);

                $tokenElement = $this->document->createElement('resumptionToken', $token->id);
                $tokenElement->setAttribute('expirationDate',
                    self::dbToUtc($token->expiration));
                $tokenElement->setAttribute('completeListSize', $rows);
                $tokenElement->setAttribute('cursor', $cursor + $listLimit);
                $verbElement->appendChild($tokenElement);
            }
            else if($cursor !== 0) {
                $tokenElement = $this->document->createElement('resumptionToken');
                $verbElement->appendChild($tokenElement);
            }
        }
    }


    /**
     * Stores a new resumption token record in the database
     * @access private
     * @param string $verb OAI-PMH verb for the request
     * @param string $metadataPrefix Metadata prefix
     * @param int $cursor Offset in response to begin output at
     * @param mixed $set Optional set argument
     * @param string $from Optional from date argument
     * @param string $until Optional until date argument
     * @return OaiPmhRepositoryToken Token model object
     */
    private function createResumptionToken($verb, $metadataPrefix, $cursor, $set, $from, $until)
    {
        $tokenTable = new OaiPmhRepositoryTokenTable();
        $resumptionToken = $tokenTable->createRow();

        $resumptionToken->verb = $verb;
        $resumptionToken->metadata_prefix = $metadataPrefix;
        $resumptionToken->cursor = $cursor + 30;

        if(isset($from)) {
            $resumptionToken->from = $from;
        } else {
        	$resumptionToken->from = NULL;
        }
        if(isset($until)) {
            $resumptionToken->until = $until;
        } else {
        	$resumptionToken->until = NULL;
        }
    	if(isset($set)) {
            $resumptionToken->set = $set;
        } else {
        	$resumptionToken->set = NULL;
        }
        $resumptionToken->expiration = self::unixToDb(time() + 60 * 60);

        $resumptionToken->useragent = $this->_userAgent();
        $resumptionToken->ipaddress = $this->_ipAddress();
        $resumptionToken->save();

     return $resumptionToken;
    }


    /** Builds an array of entries for all included metadata mapping classes.
     * Derived heavily from OaipmhHarvester's getMaps().
     * Modified the method for directory iteration via dirname(__FILE__)
     * @access private
     * @return array An array, with metadataPrefix => class.
     */
    private function getFormats() {

        $dir = new DirectoryIterator( dirname(__FILE__) . '/Metadata/');
        $metadataFormats = array();
        foreach ($dir as $dirEntry) {
            if ($dirEntry->isFile() && !$dirEntry->isDot()) {
                $filename = $dirEntry->getFilename();
                // Check for all PHP files, ignore the abstract class
                if(preg_match('/^(.+)\.php$/', $filename, $match) && $match[1] != 'Abstract') {
                    $class = 'Pas_OaiPmhRepository_Metadata_'  . $match[1];
                    $object = new $class(null, null);
                    $metadataFormats[$object->getMetadataPrefix()] = $class;
                }
            }
        }

        return $metadataFormats;
    }
    /**
     * Outputs the XML response as a string
     *
     * Called once processing is complete to return the XML to the client.
     *
     * @return string the response XML
     */
    public function __toString()
    {
        return $this->document->saveXML();
    }
}
