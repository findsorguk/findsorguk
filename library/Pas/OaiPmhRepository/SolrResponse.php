<?php
/** This class fetches data from the SOLR cluster and makes it available to
 * the OAI server.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_OaiPmhRepository
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_Cache
 * @uses Solarium_Client
 * @uses Zend_Registry
 * 
 * 
 */
class Pas_OaiPmhRepository_SolrResponse {

    /** Max records to return per call
     * 
     */
    const LISTLIMIT = 30;

    /** The solr object
     * @access protected
     * @var \Solarium_Client
     */
    protected $_solr;

    /** The solr config options
     * @access protected
     * @var array
     */
    protected $_solrConfig;

    /** The cache to use
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The allowed array for higher access
     * @access protected
     * @var array
     */
    protected $_allowed =  array('fa', 'flos','admin','treasure');

    /** Construct the class
     * @access public
     * @return void
     */
    public function __construct(){
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
        $this->_solr = new Solarium_Client($this->_solrConfig);
    }

    /** Get the user's role
     * @access protected
     * @return string
     * @todo perhaps deprecate
     */
    protected function getRole(){
	$user = new Pas_User_Details();
        $person = $user->getPerson();
        if($person){
            $role =  $person->role;
        } else {
            $role = 'public';
        }
        return $role;
    }	
    
    /**  Get an individual record
     * @access public
     * @param integer $id
     * @return array
     */
    public function getRecord($id){
        $fields = array(
            'id', 'old_findID', 'creator',
            'description', 'broadperiod', 'thumbnail',
            'imagedir', 'filename', 'created',
            'institution','updated','objecttype',
            'fourFigure', 'fromdate','todate',
            'county', 'district','materialTerm',
            'knownas', 'secondaryMaterialTerm',
            'fourFigureLat', 'fourFigureLon',
            'notes', 'datefound1', 'datefound2',
            'width', 'height', 'thickness', 
            'diameter', 'weight', 'identifier', 
            'recorder', 'recorderID', 'identifierID', 
            'identifier2ID', 'secondaryIdentifer'
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

    /** Get records for paginating through dataset
     * @access public
     * @param integer $cursor
     * @param string $set
     * @param string $from
     * @param string $until
     * @return array
     */
    public function getRecords($cursor = 0, $set, $from, $until) {
        $fields = array(
            'id', 'old_findID', 'creator',
            'description', 'broadperiod', 'thumbnail',
            'imagedir', 'filename', 'created',
            'institution','updated','objecttype',
            'fourFigure', 'fromdate','todate',
            'county', 'district', 'materialTerm',
            'knownas', 'secondaryMaterialTerm', 'fourFigureLat', 
            'fourFigureLon', 'notes', 'datefound1', 
            'datefound2', 'width', 'height',
            'thickness', 'diameter', 'weight',
            'identifier', 'recorder', 'recorderID',
            'identifierID', 'identifier2ID', 'secondaryIdentifer'
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

    /** Format the date string
     * @access public
     * @param string $date_string
     * @return string
     */
    public function fromString($date_string) {
	if (is_integer($date_string) || is_numeric($date_string)) {
            return intval($date_string);
	} else {
            return strtotime($date_string);
	}
    }

    /** Format the date and return as unix stamp
     * @access public
     * @param string $date_string
     */
    public function todatestamp($date_string) {
        $date = $this->fromString($date_string);
        return date('Y-m-d\TH:i:s\Z', $date);
    }
}