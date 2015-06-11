<?php
/** A view helper to get statistics for a coin denomination from solr
 * 
 * An example of use:
 * 
 * <code>
 * <?php 
 * echo $this->coinStatsSolr()->setDenomination($id);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since 1
 * @uses Zend_View_Helper_Abstract
 * @uses Zend_Registry
 * @uses Zend_Cache
 * @uses Solarium_Client
 * @uses Zend_Config
 * @category Pas
 * @package View
 * @subpackage Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */

class Pas_View_Helper_CoinStatsSolr extends Zend_View_Helper_Abstract {
    
    /** The Solr search object
     * @access protected
     * @var Solarium_Client
     */
    protected $_solr;

    /** The cache object
     * @access protected
     * @var Zend_Cache
     */
    protected $_cache;

    /** The config object
     * @access protected
     * @var Zend_Config
     */
    protected $_config;

    /** The solr config
     * @access protected
     * @var array $_solrConfig
     */
    protected $_solrConfig;

    /** The denomination type
     * @access protected
     * @var int
     */
    protected $_denomination;

    /** The cache key to query
     * @access protected
     * @var string
     */
    protected $_cacheKey;
    
    /** Get the denomination to query
     * @access public
     * @return int $_denomination
     */
    public function getDenomination() {
        return $this->_denomination;
    }

    /** Set the denomination
     * 
     * @param int $denomination
     * @return \Pas_View_Helper_CoinStatsSolr
     */
    public function setDenomination($denomination) {
        $this->_denomination = $denomination;
        return $this;
    }

    /** Get the solr object
     * @access public
     * @return \Solarium_Client $_solr;
     */
    public function getSolr() {
        $this->_solr = new Solarium_Client($this->getSolrConfig());
        return $this->_solr;
    }

    /** Get the cache object
     * @access public
     * @return Zend_Config $_config;
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config
     * @access public
     * @return Zend_Config $_config;
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the solr config
     * @access public
     * @return array $_solrConfig;
     */
    public function getSolrConfig() {
        $this->_solrConfig = array(
            'adapteroptions' => $this->getConfig()->solr->toArray()
                );
        return $this->_solrConfig;
    }

    /** Get the cache key
     * @access public
     * @return string $_cacheKey;
     */
    public function getCacheKey() {
        $this->_cacheKey = md5('coinstats' . $this->getDenomination());
        return $this->_cacheKey;
    }

    /** The view helper class for returning
     * @access public
     * @return \Pas_View_Helper_CoinStatsSolr
     */
    public function coinStatsSolr(){

        return $this;
    }

    /** The html generator
     * @access public
     * @return string
     */
    public function html(){
        $html = '';
        $data = $this->getSolrResults($this->getDenomination());
        if($data) {
            $html .= '<h3 class="lead">Statistics for coins recorded</h3>';
            $html .= '<p>This will possibly highlight a lot of mistakes in data entry.</p>';
            foreach ($data as $key => $value) {
                $html .= '<h4 class="lead">';
                $html .= ucfirst($key); 
                $html .= '</h4><ul>';
                if ($key != 'quantity') {
                    unset($value['total']);
                }
                foreach ($value as $k => $v) {
                    $html .= '<li>';
                    $html .= ucfirst($k);
                    $html .= ': ';
                    $html .= number_format($v,2);
                    $html .= '</li>';
                }
                $html .= '</ul>';
                }
        }
    return $html;
    }

    /** The magic method to return the string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->html();
    }

    /** Return the solr results
     * @access public
     * @return array
     */
    public function getSolrResults() {
        if (!($this->getCache()->test($this->getCacheKey()))) {
            $select = array(
                'query' => 'denomination:' . $this->getDenomination(),
                );
            $query = $this->getSolr()->createSelect($select);
            $stats = $query->getStats();
            $stats->createField('diameter');
            $stats->createField('weight');
            $stats->createField('thickness');
            $stats->createField('quantity');
            $resultset = $this->getSolr()->select($query);
            $data = $resultset->getStats();
            $statistics = array();
            foreach ($data as $result) {
                $statistics[$result->getName()] = array(
                    'total' => $result->getSum(),
                    'records' => $result->getCount(),
                    'mean' => $result->getMean(),
                    'maxima' => $result->getMax(),
                    'minima' => $result->getMin()
                        );
            }
            $this->getCache()->save($statistics);
            } else {
                $statistics = $this->getCache()->load($this->getCacheKey());
            }
        return $statistics;
    }
}
