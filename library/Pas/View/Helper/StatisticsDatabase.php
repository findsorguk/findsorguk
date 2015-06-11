<?php
/**
 * Statistics from the Database view helper. This draws data from the solr server.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $this->statisticsDatabase();
 * ?>
 * </code>
 *
 * This view helper queries the solr objects index and gets count for objects and records
 * @category Pas
 * @package View
 * @subpackage Helper
 * @author Daniel Pett dpett@britishmuseum.org
 * @uses viewHelper Pas_View_Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example app/modules/database/views/scripts/index/index.phtml
 * @version 1
 *
 */

class Pas_View_Helper_StatisticsDatabase extends Zend_View_Helper_Abstract
{
    /** The Solarium client
     * @access protected
     * @var Solarium_Client
     */
    protected $_solr;

    /** Solr configuration object
     * @var array
     */
    protected $_solrConfig;

    /** Construct the values and objects
     * @access public
     * @retunr void
     */
    public function __construct()
    {
        $config = Zend_Registry::get('config');
        $this->_solrConfig = array('adapteroptions' => $config->solr);
        $this->_solr = new Solarium_Client($this->_solrConfig);
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_StatisticsDatabase
     */
    public function statisticsDatabase()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     * @uses partials/database/structural/heroCounts.phtml
     */
    public function __toString() {
        return $this->view->partial('partials/database/structural/heroCounts.phtml',$this->getSolrResults());
    }

    /** Get the solr data
     * @access public
     * @return array
     */
    public function getSolrResults()
    {
        $query = $this->_solr->createSelect();
        $query->setRows(0);
        $stats = $query->getStats();
        $stats->createField('quantity');
        $resultset = $this->_solr->select($query);
        $data = $resultset->getStats();
        $stats = array();
        // Create array of data for use in partial
        foreach ($data as $result) {
            $stats['total'] = $result->getSum();
            $stats['records'] = $result->getCount();
        }
        return $stats;
    }
}