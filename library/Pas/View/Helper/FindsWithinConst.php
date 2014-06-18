<?php
/** A view helper for getting a count of SMR records within a constituency
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @license GNU
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @version 1
 * @since 2/2/12
 * @uses viewHelper Pas_View_Helper extends Zend_View_Helper_Abstract
 */
class Pas_View_Helper_FindsWithinConst extends Zend_View_Helper_Abstract
{
    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The constituency to query
     * @access protected
     * @var string
     */
    protected $_constituency;

    /** Get the constituency
     * @access public
     * @return string
     */
    public function getConstituency() {
        return $this->_constituency;
    }

    /** Set the constituency
     * @access public
     * @param string $constituency
     * @return \Pas_View_Helper_FindsOfNoteConst
     */
    public function setConstituency( string $constituency) {
        $this->_constituency = $constituency;
        return $this;
    }

    /** Get the cache
     * @access public
     * @return object
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** The bounding box string to return to the view
     * @access protected
     * @var string
     */
    protected $_geometry;

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_FindsOfNoteConst
     */
    public function findsWithinConst() {
        return $this;
    }

    /** To string method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getData($this->getConstituency());
    }

    /** Get the constituency's geometry
     * @access public
     * @param string $constituency
     * @return object
     */
    public function getGeometry(string $constituency) {
        $geo = new Pas_Twfy_Geometry();
        return $geo->get($constituency);
    }

    /** Get the data for the constituency
     * @access public
     * @param string $constituency
     * @return string
     */
    public function getData($constituency) {
        $data = $this->getSolr($constituency);
        return $this->buildHtml($data, $constituency);
    }



    /** Get the data from the solr index
     * @access public
     * @param string $constituency
     * @return int
     */
    public function getSolr(string $constituency){
        $geometry = $this->getGeometry($constituency);
        $bbox = array(
            $geometry->min_lat,
            $geometry->min_lon,
            $geometry->max_lat,
            $geometry->max_lon);
	$search = new Pas_Solr_Handler('beowulf');
        $search->setFields(array(
            'id', 'identifier', 'objecttype',
            'title', 'broadperiod','imagedir',
            'filename','thumbnail','old_findID',
            'description', 'county')
            );
	$search->setParams(array('bbox' => implode(',',$bbox)));
        $search->execute();
        $this->_geometry = implode(',', $bbox);
        return $search->getNumber();
    }

    /** Build the html
     * @param int $data
     * @param string $constituency
     * @return string
     */
    public function buildHtml(int $data, string $constituency){
        $html = '';
	if($data > 0){
        $url = $this->view->url(array(
            'module' => 'news',
            'controller' => 'theyworkforyou',
            'action' => 'finds',
            'constituency' => $constituency,
            ),'default',true);
	$html .= '<p>There are <a href="';
        $html .= $url;
        $html .= '" title ="View finds for this constituency">';
        $html .= $data;
        $html .= ' finds</a> recorded in this constituency.</p>';
	}
        return $html;
    }
}

