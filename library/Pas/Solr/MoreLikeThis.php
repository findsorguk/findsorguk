<?php
/** A Solr class for dealing with more like this
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $solr = new Pas_Solr_MoreLikeThis();
 * ?>
 * </code>
 *
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Solr
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /library/Pas/View/Helper/MoreLikeThis.php
 */
class Pas_Solr_MoreLikeThis {

    /** The solr instance
     * @access protected
     * @var \Solarium_Client
     */
    protected $_solr;

    /** The index to query
     * @access protected
     * @var string
     */
    protected $_index;

    /** The limit of records to query
     * @access protected
     * @var integer
     */
    protected $_limit;

    /** The cache object
     * @access protected
     * @var \Zend_Cache
     */
    protected $_cache;

    /** The config object
     * @access protected
     * @var \Zend_Config
     */
    protected $_config;

    /** The solr configuration array
     * @access protected
     * @var array
     *
     */
    protected $_solrConfig;

    /** The constructor
     *
     */
    public function __construct(){
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = array(
            'adapteroptions' => $this->_config->solr->toArray()
                );
        $this->_solr = new Solarium_Client($this->_solrConfig);
        $this->_solr->setAdapter('Solarium_Client_Adapter_ZendHttp');
        $loadbalancer = $this->_solr->getPlugin('loadbalancer');
        $master = $this->_config->solr->master->toArray();
        $slave  = $this->_config->solr->slave->toArray();
        $loadbalancer->addServer('master', $master, 100);
        $loadbalancer->addServer('slave', $slave, 200);
        $loadbalancer->setFailoverEnabled(true);
    }

    /** Get the User Deatils
     * @return mixed|null
     */
    public function getUserDetails()
    {
	$user = new Pas_User_Details();
        $person = $user->getPerson();
        return $person;
    }

    /** Get the role of the user
     * @access public
     * @return string
     */
    public function getRole(){
	//$user = new Pas_User_Details();
        //$person = $user->getPerson();
	$person = $this->getUserDetails();
        if($person){
            return $person->role;
        } else {
            return 'public';
        }
    }

    /** Get the id of the user
     * @access public
     * @return int
     */
    public function getUserID()
    {
	$person = $this->getUserDetails();
        if($person){
            return $person->id;
        } else {
            return null;
        }
    }

    /** Roles allowed for higher level access
     * @access public
     * @var type
     */
    protected $_allowed =  array(
        'fa', 'flos', 'admin',
        'treasure', 'hoard'
    );

    /** Set the fields to query for likeness
     * @access public
     * @param array $fields
     * @throws Pas_Solr_Exception
     */
    public function setFields(array $fields){
        if(is_array($fields)){
            $this->_fields = implode($fields,',');
        } else {
            throw new Pas_Solr_Exception('The field list is not an array');
        }
    }

    /** Set the query
     * @access public
     * @param string $query
     * @throws Pas_Solr_Exception
     */
    public function setQuery($query){
        if(is_string($query)){
            $this->_query = (string)$query;
        } else {
            throw new Pas_Solr_Exception('query must be a string');
        }
    }

    /** Execute the query
     * @access public
     * @param integer $minDocFreq
     * @param integer $minTermFreq
     * @param integer $count
     * @return array
     */
    public function executeQuery( $minDocFreq = 1, $minTermFreq = 1, $count = 9){
        $client = $this->_solr;
        $query = $client->createSelect();
        $query->setQuery($this->_query)
                ->getMoreLikeThis()
                ->setFields($this->_fields)
                ->setMinimumDocumentFrequency($minDocFreq)
                ->setMinimumTermFrequency($minTermFreq)
                ->setCount($count);

        $resultset = $client->select($query); 
        $mlt = $resultset->getMoreLikeThis();
        $mltArray = array();

	$restrictedWorkflows = Array(1, 2);
	$maxMLTRecordsToShow = 3;
	$validMLTResults = array();
	$validRecordsFound = 0;
        $userCanSeeRedRecords = in_array($this->getRole(), $this->_allowed);

	foreach ($mlt as $allMLTkeys => $allMLTResults) {
		if (!is_object($allMLTResults)) {
			break;
		}
        	foreach ($allMLTResults as $oneMLTRecord) {
	      		if (count($validMLTResults) === $maxMLTRecordsToShow) {
                        	break;
	                }

			$recordCreatedBy = $oneMLTRecord->createdBy;
                        if ((in_array($oneMLTRecord->workflow, $restrictedWorkflows) 
			  && !($recordCreatedBy == $this->getUserID()))
                          && !$userCanSeeRedRecords) {
				continue;
                        }

			$validMLTResults[$validRecordsFound++] = $oneMLTRecord;
		}
	}
	
        if ($validMLTResults) {
                $mltArray['maxScore'] = $allMLTResults->getMaximumScore();
                $mltArray['numFound'] = $allMLTResults->getNumFound();
                $mltArray['numFetched'] = $validRecordsFound;
                foreach ($validMLTResults AS $k => $v) {
                	$mltArray['results'][$k] = $v;
                }
        }
        return $mltArray;
    }
}
