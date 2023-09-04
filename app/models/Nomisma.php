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

    /** The cache object
     * @var  $_cache
     * @access protected
     */
    protected $_cache;

    /** A method to turn RRC rdf into dropdowns in the format of id and term pairs
     * @access public
     * @return array $dropdown
     */
    public function getRRCDropdowns($identifier)
    {
        $rrcTypes = $this->getRRCTypes($identifier);
        $dropDown = array();
        foreach ($rrcTypes as $rrcType) {
            $dropDown[] = array(
                'id' => str_replace('http://numismatics.org/crro/id/', '', $rrcType->type->__toString()),
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
        foreach ($rrcTypes as $rrcType) {
            $dropDown[] = array(
                'id' => str_replace('http://numismatics.org/ocre/id/', '', $rrcType->type->__toString()),
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
        $checkHeaders = get_headers('http://nomisma.org/apis');

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
        if (!($this->getCache()->test($key))) {
            //Add the namespaces needed to parse the query
            \EasyRdf\RdfNamespace::set('nm', 'http://nomisma.org/id/');
            \EasyRdf\RdfNamespace::set('nmo', 'http://nomisma.org/ontology#');
            \EasyRdf\RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
            \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
            try {
                $sparql = new Pas_RDF_EasyRdf_Client('http://nomisma.org/query');
                $data = $sparql->query(
                    'SELECT * WHERE {' .
                    '  ?type ?role nm:' . $identifier . ' ;' .
                    '   a nmo:TypeSeriesItem ;' .
                    '  skos:prefLabel ?label' .
                    '  FILTER(langMatches(lang(?label), "en"))' .
                    '  OPTIONAL {?type nmo:hasStartDate ?startDate}' .
                    '  OPTIONAL {?type nmo:hasEndDate ?endDate}' .
                    ' } ORDER BY ?label');
                $this->getCache()->save($data);
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
     * @return mixed
     */
    public function getCache()
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
        foreach ($ricTypes as $ricType) {
            $dropDown[str_replace('http://numismatics.org/ocre/id/', '', $ricType->type->__toString())] = $ricType->label->__toString();
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
        if (!($this->getCache()->test($key))) {
            //Add the namespaces needed to parse the query
            \EasyRdf\RdfNamespace::set('nm', 'http://nomisma.org/id/');
            \EasyRdf\RdfNamespace::set('nmo', 'http://nomisma.org/ontology#');
            \EasyRdf\RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
            \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
            try {
                $sparql = new Pas_RDF_EasyRdf_Client('http://nomisma.org/query');
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
                $this->getCache()->save($data);
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
        foreach ($rrcTypes as $rrcType) {
            $dropDown[str_replace('http://numismatics.org/crro/id/', '', $rrcType->type->__toString())] = $rrcType->label->__toString();
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
            \EasyRdf\RdfNamespace::set('nm', 'http://nomisma.org/id/');
            \EasyRdf\RdfNamespace::set('nmo', 'http://nomisma.org/ontology#');
            \EasyRdf\RdfNamespace::set('skos', 'http://www.w3.org/2004/02/skos/core#');
            \EasyRdf\RdfNamespace::set('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
            $sparql = new Pas_RDF_EasyRdf_Client('http://nomisma.org/query');
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
