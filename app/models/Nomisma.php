<?php

/** A class for interacting with the Nomisma remote triple store (http://nomisma.org)
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $nomisma = new Nomisma();
 * $nomisma->getRRCDropdowns('cassius');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett/ Trustees of the British Museum
 * @category Pas
 * @package Nomisma
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /library/Pas/Controller/Action/Helper/CoinFormLoaderOptions.php
 */
class Nomisma
{

    private int $cacheTimeout;

    /** The cache object
     * @var  $_cache
     * @access protected
     */
    protected $_cache;
    private $config;

    public function __construct()
    {
        $this->config = $this->_helper->Config;
        $this->cacheTimeout = $this->_helper->Config->get('settings', 'application', 'nomisma', 'cache');
    }

    /** A method to turn RRC rdf into dropdowns in the format of id and term pairs
     * @access public
     * @return array $dropdown
     */
    public function getRRCDropdowns($identifier)
    {
        $rrcTypes = $this->getRRCTypes($identifier);
        $dropDown = array();
        $url = $this->config->getWebserviceValue('numismatics', 'src') .
            $this->config->getWebserviceValue('numismatics', 'rrc');

        foreach ($rrcTypes as $rrcType) {
            $dropDown[] = array(
                'id' => str_replace($url, '', $rrcType->type->__toString()),
                'term' => $rrcType->label->__toString()
            );
        }
        return $dropDown;
    }


    /** A method to turn RRC rdf into dropdowns in the format of id and term pairs
     * @access public
     * @return array $dropdown
     */
    public function getRICDropdowns($identifier)
    {
        $rrcTypes = $this->getRRCTypes($identifier);
        $dropDown = array();
        $url = $this->config->getWebserviceValue('numismatics', 'src') .
            $this->config->getWebserviceValue('numismatics', 'ric');

        foreach ($rrcTypes as $rrcType) {
            $dropDown[] = array(
                'id' => str_replace($url, '', $rrcType->type->__toString()),
                'term' => $rrcType->label->__toString()
            );
        }
        return $dropDown;
    }

    /**Send Nomisma error email
     * @param $error
     * @param string $type
     * @return void
     * @throws Zend_Mail_Exception|Zend_Exception
     */
    public function sendErrorEmail($errorDescription, string $errorType)
    {
        $mailer = (new Pas_Controller_Action_Helper_Mailer());
        $mailer->init();
        $mailer->direct(compact('errorType', 'errorDescription'),
            'nomismaError',
            array_map(function ($email, $name) { return ['email' => $email, 'name' => $name]; },
                Zend_Registry::get('config')->admin->email->toArray(),
                Zend_Registry::get('config')->admin->name->toArray()
            )
        );
    }

    /**Check Nomisma site status by looking at header values
     * @return bool
     * @throws Zend_Mail_Exception
     */
    public function getStatusNomisma()
    {
        stream_context_set_default([
            'http' => [
                'timeout' => 3, // seconds
            ]
        ]);
        $url = $this->config->getWebserviceValue('nomisma', 'src') . $this->config->getWebserviceValue('nomisma', 'api');
        $checkHeaders = get_headers($url);

        if (preg_match('/(2|3)[0-9][0-9]/', $checkHeaders[0]) == false) {
            $this->sendErrorEmail('Nomisma did not return status code 200/400', 'HTTP response code');
            return false;
        }

        return true;
    }

    /** Get the data for reuse based off sparql endpoint
     * @access public
     * @return array $data
     * */
    public function getRRCTypes($identifier)
    {
        $key = md5($identifier . 'rrcTypes');
        $nomismaSrc = $this->config->getWebserviceValue('nomisma', 'src');
        $w3Src = $this->config->getWebserviceValue('w3', 'src');
        if (!($this->getCache()->test($key))) {
            //Add the namespaces needed to parse the query
            \EasyRdf\RdfNamespace::set('nm', $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'nm'));
            \EasyRdf\RdfNamespace::set('nmo', $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'nmo'));
            \EasyRdf\RdfNamespace::set('skos', $w3Src . $this->config->getWebserviceValue('w3', 'skos'));
            \EasyRdf\RdfNamespace::set('rdf', $w3Src . $this->config->getWebserviceValue('w3', 'rdf'));
            try {
                $sparqlUri = $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'query');
                $sparql = new Pas_RDF_EasyRdf_Client($sparqlUri);
                $data = $sparql->query(
                    'SELECT * WHERE {' .
                    '  ?type ?role nm:' . $identifier . ' ;' .
                    '   a nmo:TypeSeriesItem ;' .
                    '  skos:prefLabel ?label' .
                    '  FILTER(langMatches(lang(?label), "en"))' .
                    '  OPTIONAL {?type nmo:hasStartDate ?startDate}' .
                    '  OPTIONAL {?type nmo:hasEndDate ?endDate}' .
                    ' } ORDER BY ?label');
                $this->getCache()->save($data, $key, array('RRC'), $this->cacheTimeout);
            } catch (Exception $e) {
                $this->sendErrorEmail($e, 'RRC');
            }
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }

    /** Get the cache object
     * @access public
     * @return Zend_Cache_Core
     * @throws Zend_Exception
     */
    public function getCache(): Zend_Cache_Core
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** A function for flattened RIC dropdowns
     * @access public
     * @return array $dropDown
     */
    public function getRICDropdownsFlat($identifier)
    {
        $ricTypes = $this->getRICTypes($identifier);
        $dropDown = array();
        $url = $this->config->getWebserviceValue('nomisma', 'src') . $this->config->getWebserviceValue('nomisma', 'ric');
        foreach ($ricTypes as $ricType) {
            $dropDown[str_replace($url,
                '', $ricType->type->__toString())] = $ricType->label->__toString();
        }
        return $dropDown;
    }

    /** Get the data for reuse based off sparql endpoint
     * @access public
     * @return array $data
     * */
    public function getRICTypes($identifier)
    {
        $key = md5($identifier . 'ricTypes');
        $nomismaSrc = $this->config->getWebserviceValue('nomisma', 'src');
        $w3Src = $this->config->getWebserviceValue('w3', 'src');

        if (!($this->getCache()->test($key))) {
            //Add the namespaces needed to parse the query
            \EasyRdf\RdfNamespace::set('nm', $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'nm'));
            \EasyRdf\RdfNamespace::set('nmo', $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'nmo'));
            \EasyRdf\RdfNamespace::set('skos', $w3Src . $this->config->getWebserviceValue('w3', 'skos'));
            \EasyRdf\RdfNamespace::set('rdf', $w3Src . $this->config->getWebserviceValue('w3', 'rdf'));
            try {
                $sparqlUri = $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'query');
                $sparql = new Pas_RDF_EasyRdf_Client($sparqlUri);
                $data = $sparql->query(
                    'SELECT * WHERE {' .
                    '  ?type ?role nm:' . $identifier . ' ;' .
                    '   a nmo:TypeSeriesItem ;' .
                    '  skos:prefLabel ?label' .
//                '  OPTIONAL {?type nmo:hasStartDate ?startDate}' .
//                '  OPTIONAL {?type nmo:hasEndDate ?endDate}' .
                    '  FILTER(langMatches(lang(?label), "en"))' .
                    ' } ORDER BY ?label'
                );
                $this->getCache()->save($data, $key, array('RIC'), $this->cacheTimeout);
            } catch (Exception $e) {
                $this->sendErrorEmail($e, 'RIC');
            }
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }

    /** A function for flattened RRC dropdowns
     * @access public
     * @return array $dropDown
     */
    public function getRRCDropdownsFlat($identifier)
    {
        $rrcTypes = $this->getRRCTypes($identifier);
        $dropDown = array();
        $url = $this->config->getWebserviceValue('numismatics', 'src') .
            $this->config->getWebserviceValue('numismatics', 'rrc');
        foreach ($rrcTypes as $rrcType) {
            $dropDown[str_replace($url, '', $rrcType->type->__toString())] = $rrcType->label->__toString();
        }
        return $dropDown;
    }

    /** A basic HTML response check using curl to check identifier exists
     * @access public
     * @return array
     */
    public function checkType($identifier)
    {
        $key = md5($identifier . 'CheckRrcTypes');
        $nomismaSrc = $this->config->getWebserviceValue('nomisma', 'src');
        $w3Src = $this->config->getWebserviceValue('w3', 'src');

        if (!($this->getCache()->test($key))) {
            $client = new  \Zend\Http\Client(
                null,
                array(
                    'adapter' => 'Zend_Http_Client_Adapter_Curl',
                    'keepalive' => true,
                    'useragent' => "finds.org.uk/easyrdf"
                )
            );
            $client->setHeaders(array('accept' => 'application/sparql-results+xml'));
            \EasyRdf\Http::setDefaultHttpClient($client);
            \EasyRdf\RdfNamespace::set('nm', $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'nm'));
            \EasyRdf\RdfNamespace::set('nmo', $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'nmo'));
            \EasyRdf\RdfNamespace::set('skos', $w3Src . $this->config->getWebserviceValue('w3', 'skos'));
            \EasyRdf\RdfNamespace::set('rdf', $w3Src . $this->config->getWebserviceValue('w3', 'rdf'));

            $sparqlUri = $nomismaSrc . $this->config->getWebserviceValue('nomisma', 'query');
            $sparql = new Pas_RDF_EasyRdf_Client($sparqlUri);
            $data = $sparql->query(
                'SELECT * WHERE {' .
                '  ?type ?role nm:' . $identifier . ' ;' .
                '   a nmo:TypeSeriesItem ;' .
                '  skos:prefLabel ?label' .
                '  OPTIONAL {?type nmo:hasStartDate ?startDate}' .
                '  OPTIONAL {?type nmo:hasEndDate ?endDate}' .
                '  FILTER(langMatches(lang(?label), "en"))' .
                ' } ORDER BY ?label');
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($key);
        }
        return $data;
    }
}
