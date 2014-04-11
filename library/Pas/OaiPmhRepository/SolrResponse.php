<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OaiResponse
 *
 * @author danielpett
 */
class Pas_OaiPmhRepository_SolrResponse {

    const LISTLIMIT = 30;

    protected $_solr;

    protected $_solrConfig;



    protected $_cache;

    protected $_allowed =  array('fa','flos','admin','treasure');

    public function __construct(){
    $this->_cache = Zend_Registry::get('cache');
    $this->_config = Zend_Registry::get('config');
    $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
    $this->_solr = new Solarium_Client($this->_solrConfig);
    }

    protected function getRole(){
	$user = new Pas_User_Details();
    $person = $user->getPerson();
    if($person){
    	return $person->role;
    } else {
    	throw new Pas_Exception_BadJuJu('No user credentials found', 500);
    }
    }	
    

    public function getRecord($id){
    $fields = array(
        'id', 'old_findID', 'creator',
        'description', 'broadperiod', 'thumbnail',
        'imagedir', 'filename', 'created',
        'institution','updated','objecttype',
        'fourFigure', 'fromdate','todate',
        'county', 'district','materialTerm',
        'knownas', 'secondaryMaterialTerm',
    	'fourFigureLat', 'fourFigureLon'

    );
    $select = array(
    'query'         => 'id:' . (int)$id,
    'rows'          => 1,
    'fields'        => $fields,
    'filterquery' => array(),
    );

    if(!in_array($this->getRole(),$this->_allowed)) {
    $select['filterquery']['workflow'] = array('query' => 'workflow:[3 TO 4]');
    }
    $query = $this->_solr->createSelect($select);
    $resultset = $this->_solr->select($query);
    $data = array();
    $data['numberFound'] = $resultset->getNumFound();
    foreach($resultset as $doc){
	$fields = array();
            foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    }
	$data['finds'][] = $fields;
	}

    return $data;
    }

    public function getRecords($cursor = 0, $set, $from, $until) {

    $fields = array(
        'id', 'old_findID', 'creator',
        'description', 'broadperiod', 'thumbnail',
        'imagedir', 'filename', 'created',
        'institution','updated','objecttype',
        'fourFigure', 'fromdate','todate',
        'county', 'district', 'materialTerm',
        'knownas', 'secondaryMaterialTerm', 'fourFigureLat', 'fourFigureLon'

    );
    $select = array(
    'query'         => '*:*',
    'start'         => $cursor,
    'rows'          => self::LISTLIMIT,
    'fields'        => $fields,
    'sort'          => array('created' => 'asc'),
    'filterquery' => array(),
    );

    if(!in_array($this->getRole(),$this->_allowed)) {
    $select['filterquery']['workflow'] = array('query' => 'workflow:[3 TO 4]');
    }
    if(isset($set)){
    $select['filterquery']['set'] = array( 'query' => 'institution:' . $set );
    }
    if(isset($from)){
    $select['filterquery']['from'] = array( 'query' => 'created:[' . $this->todatestamp($from) . ' TO * ]' );
    }

    if(isset($until)){
    $select['filterquery']['until'] = array( 'query' => 'created:[* TO ' . $this->todatestamp($until) . ']' );
    }



    $query = $this->_solr->createSelect($select);
    $resultset = $this->_solr->select($query);
    $data = array();
    $data['numberFound'] = $resultset->getNumFound();
    foreach($resultset as $doc){
	$fields = array();
            foreach($doc as $key => $value){
	    	$fields[$key] = $value;
	    }
	$data['finds'][] = $fields;
	}

    return $data;
    }

    public function fromString($date_string) {
	if (is_integer($date_string) || is_numeric($date_string)) {
	return intval($date_string);
	} else {
	return strtotime($date_string);
	}
	}

    /** Format the date and return as unix stamp
	*
	* @param string $date_string
	*/
	public function todatestamp($date_string) {
	$date = $this->fromString($date_string);
	$ret = date('Y-m-d\TH:i:s\Z', $date);
	return $ret;
	}
}
