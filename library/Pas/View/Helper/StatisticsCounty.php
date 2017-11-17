<?php
/**
 * StatisticsDatabase helper
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_StatisticsCounty extends Zend_View_Helper_Abstract
{
    protected $_solr;

    protected $_index;

    protected $_limit;

    protected $_cache;

    protected $_config;

    protected $_solrConfig;

    protected $_start;

    protected $_end;

    protected $_county;

    protected $_request;

    public function __construct()
    {
        $this->_cache = Zend_Registry::get('cache');
        $this->_config = Zend_Registry::get('config');
        $this->_solrConfig = array('adapteroptions' => $this->_config->solr->toArray());
        $this->_solr = new Solarium_Client($this->_solrConfig);
        $this->_request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    }

    public function statisticsCounty()
    {
        return $this;
    }

    private function getSolrResults()
    {
        $select = array(
            'query' => '*:*',
            'filterquery' => array(),
        );

        $query = $this->_solr->createSelect();
        $query->setRows(0);

        if (array_key_exists('county', $this->_request)) {
            $query->createFilterQuery('county')->setQuery('county:' . $this->_request['county']);
        }
        if (!array_key_exists('datefrom', $this->_request)) {
            $timespan = new Pas_Analytics_Timespan('thisyear');
            $dates = $timespan->getDates();
            $queryDateA = $dates['start'] . "T00:00:00.001Z";
            $queryDateB = $dates['end'] . "T23:59:59.99Z";
            $query->createFilterQuery('created')->setQuery('created:[' . $queryDateA . ' TO ' . $queryDateB . ']');
        } else {
            $queryDateA = $this->_request['datefrom'] . "T00:00:00.001Z";
            $queryDateB = $this->_request['dateto'] . "T23:59:59.99Z";
            $query->createFilterQuery('created')->setQuery('created:[' . $queryDateA . ' TO ' . $queryDateB . ']');
        }

        $stats = $query->getStats();

        $stats->createField('quantity');
        $stats->addFacet('county');
        $resultset = $this->_solr->select($query);

        $data = $resultset->getStats();
        $stats = array();
        // display the stats results
        foreach ($data as $field) {
            if(is_array($field)) {
                foreach ($field->getFacets() as $field => $facet) {
                    foreach ($facet AS $facetStats) {
                        $stats[] = array(
                            'county' => $facetStats->getValue(),
                            'finds' => $facetStats->getSum(),
                            'records' => $facetStats->getCount()
                        );
                    }
                }
            }
        }
        $sort = array();
        foreach ($stats as $k => $v) {
            $sort['county'][$k] = $v['county'];
            $sort['finds'][$k] = $v['finds'];
        }
        if (count($sort) > 1) {
            array_multisort($sort['county'], SORT_ASC, $sort['finds'], SORT_ASC, $stats);
        }
        return $stats;
    }

    public function buildHtml($data)
    {
        $html = '';

        $html .= $this->view->partialLoop('partials/annual/countiesStatistics.phtml', $data);

        return $html;
    }

    public function __toString()
    {
        return $this->buildHtml($this->getSolrResults());
    }
}
