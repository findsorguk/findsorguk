<?php
/** A view helper to get statistics for a coin denomination from solr
 * @a
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since 1
 * @uses Zend_View_Helper_Abstract
 * @uses Zend_Registry
 * @uses Zend_Cache
 * @uses Solarium_Client
 * @uses Zend_Config
 */

class Pas_View_Helper_CoinStatsSolr extends Zend_View_Helper_Abstract
{
    /** The Solr search object
     *
     * @var object
     */
    protected $_solr;

    /** The cache object
     *
     * @var type
     */
    protected $_cache;

    /** The config object
     *
     * @var type
     */
    protected $_config;

    /** The solr config
     *
     * @var type
     */
    protected $_solrConfig;

    /** The denomination type
     *
     * @var integer
     */
    protected $_denomination;

    /** The cache key to query
     *
     * @var string
     */
    protected $_cacheKey;

    /** Construct the objects
     *
     * @param int $denomination
     */
    public function __construct( $denomination ){
        $this->_denomination = $denomination;
        $this->_cache = Zend_Registry::get('cache');
        $this->_cacheKey = md5('coinstats' . $this->_denomination);
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
        $this->_solr = new Solarium_Client($this->_solrConfig);
    }

    /** The view helper class for returning
     *
     * @return \Pas_View_Helper_CoinStatsSolr
     */
    public function coinStatsSolr() {
        return $this;
    }

    /** The html generator
     *
     * @return string
     */
    public function html() {
        $data = $this->getSolrResults($this->_denomination);
        $html = '<h3>Statistics for coins recorded</h3>';
	$html .= '<p>This will possibly highlight a lot of mistakes in data entry.</p>';
	foreach($data as $key => $value){
		$html .= '<h4>' . ucfirst($key) . '</h4><ul>';
		if($key != 'quantity'){
			unset($value['total']);
		}
		foreach($value as $k => $v){
			$html .= '<li>' . ucfirst($k) . ': ' . number_format($v,2) . '</li>';
		}
		$html .= '</ul>';
	}
	return $html;
    }

    /** The magic method to return the string
     *
     * @return string
     */
    public function __toString() {
        return $this->html();
    }


    /** Return the solr results
     *
     * @return type
     */
    public function getSolrResults() {
        if (!($this->_cache->test($this->_cacheKey))) {
            $select = array(
                'query' => 'denomination:' . $this->_denomination,
	    );
            $query = $this->_solr->createSelect($select);
            $stats = $query->getStats();
            $stats->createField('diameter');
            $stats->createField('weight');
            $stats->createField('thickness');
            $stats->createField('quantity');
            $resultset = $this->_solr->select($query);
            $data = $resultset->getStats();
            $statistics = array();
            foreach($data as $result){
                $statistics[$result->getName()] = array(
                    'total' => $result->getSum(),
                    'records' => $result->getCount(),
                    'mean' => $result->getMean(),
                    'maxima' => $result->getMax(),
                    'minima' => $result->getMin()
                        );
            }
            $this->_cache->save($statistics);
            } else {
                $statistics = $this->_cache->load($this->_cacheKey);
            }
            return $statistics;
	}
}