<?php

/**
 * This class returns data from the SOlR engine for use in the OAI engine.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 *
 */
class Pas_OaiPmhRepository_SolrResponse
{

    /** The set limit for response
     *
     */
    const LISTLIMIT = 100;

    /** The Solarium client
     * @access protected
     * @var Solarium_Client
     */
    protected $_solr;

    /** The solr config array
     * @var array
     */
    protected $_solrConfig;

    /** The cache object
     * @access protected
     * @var Zend_Cache
     */
    protected $_cache;

    /** The array of protected roles
     * @access protected
     * @var array
     */
    protected $_allowed = array('fa', 'flos', 'admin', 'treasure', 'hoard');

    /** Construct the class
     * @access public
     */
    public function __construct()
    {
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
        $this->_solr = new Solarium_Client($this->_solrConfig);
    }

    /** Get the role of the user
     * @access protected
     * @return mixed
     */
    protected function getRole()
    {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
            return $person->role;
        } else {
            return null;
        }
    }


    /** Get a record by the ID
     * @access public
     * @return array
     */
    public function getRecord($id)
    {
        $fields = array(
            'id', 'old_findID', 'creator',
            'description', 'broadperiod', 'thumbnail',
            'imagedir', 'filename', 'created',
            'institution', 'updated', 'objecttype',
            'fourFigure', 'fromdate', 'todate',
            'county', 'district', 'materialTerm',
            'knownas', 'secondaryMaterialTerm',
            'fourFigureLat', 'fourFigureLon'

        );
        $select = array(
            'query' => 'id:' . (int)$id,
            'rows' => 1,
            'fields' => $fields,
            'filterquery' => array(),
        );

        if (!in_array($this->getRole(), $this->_allowed)) {
            $select['filterquery']['workflow'] = array('query' => 'workflow:[3 TO 4]');
        }
        $query = $this->_solr->createSelect($select);
        $resultset = $this->_solr->select($query);
        $data = array();
        $data['numberFound'] = $resultset->getNumFound();
        foreach ($resultset as $doc) {
            $fields = array();
            foreach ($doc as $key => $value) {
                $fields[$key] = $value;
            }
            $data['finds'][] = $fields;
        }
        return $data;
    }

    /** Get records from solr
     * @access public
     * @return array
     */
    public function getRecords($cursor = 0, $set, $from, $until)
    {

        $fields = array(
            'id', 'old_findID', 'creator',
            'description', 'broadperiod', 'thumbnail',
            'imagedir', 'filename', 'created',
            'institution', 'updated', 'objecttype',
            'fourFigure', 'fromdate', 'todate',
            'county', 'district', 'materialTerm',
            'knownas', 'secondaryMaterialTerm', 'fourFigureLat',
            'fourFigureLon'
        );
        $select = array(
            'query' => '*:*',
            'start' => $cursor,
            'rows' => self::LISTLIMIT,
            'fields' => $fields,
            'sort' => array('created' => 'asc'),
            'filterquery' => array(),
        );

        if (!in_array($this->getRole(), $this->_allowed)) {
            $select['filterquery']['workflow'] = array('query' => 'workflow:[3 TO 4]');
        }
        if (isset($set)) {
            $select['filterquery']['set'] = array('query' => 'institution:' . $set);
        }
        if (isset($from)) {
            $select['filterquery']['from'] = array('query' => 'created:[' . $this->todatestamp($from) . ' TO * ]');
        }

        if (isset($until)) {
            $select['filterquery']['until'] = array('query' => 'created:[* TO ' . $this->todatestamp($until) . ']');
        }


        $query = $this->_solr->createSelect($select);
        $resultset = $this->_solr->select($query);
        $data = array();
        $data['numberFound'] = $resultset->getNumFound();
        foreach ($resultset as $doc) {
            $fields = array();
            foreach ($doc as $key => $value) {
                $fields[$key] = $value;
            }
            $data['finds'][] = $fields;
        }

        return $data;
    }

    /** Convert date from string
     * @access public
     * @return string
     */
    public function fromString($date_string)
    {
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
    public function todatestamp($date_string)
    {
        $date = $this->fromString($date_string);
        $ret = date('Y-m-d\TH:i:s\Z', $date);
        return $ret;
    }
}
