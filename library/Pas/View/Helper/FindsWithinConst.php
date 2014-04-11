<?php
/**
 *
 * @author dpett
 * @version
 */

/**
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
class Pas_View_Helper_FindsWithinConst extends Zend_View_Helper_Abstract {


	/** The cache object
	 *
	 */
	protected $_cache;


        /** String of bbox coordinates
         *
         * @var type
         */
        protected $_geometry;

        /** Get the cache and config
         *
         */
	public function __construct(){
	$this->_cache = Zend_Registry::get('cache');
	}

        /** Build and return finds of note count
         *
         * @param type $const
         * @return type
         */
        public function findsWithinConst($constituency) {
	return $this->getData($constituency);
	}


        public function getGeometry($constituency){
        $geo = new Pas_Twfy_Geometry();
        return $geo->get($constituency);
        }

        /** Get the data from solr
         *
         * @param string $constituency
         * @return int
         */
        public function getSolr($constituency){
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


        /** Get the finds in that constituency
         * @todo change to solr
         * @param type $const
         * @return boolean
         */
	public function getData($constituency) {

	$data = $this->getSolr($constituency);

        return $this->buildHtml($data, $constituency);

	}


        /** Build the html
         *
         * @param int $data
         * @return string|boolean
         */
	public function buildHtml($data, $constituency){
	if($data > 0){
        $url = $this->view->url(array(
            'module' => 'news',
            'controller' => 'theyworkforyou',
            'action' => 'finds',
            'constituency' => $constituency,
            ),'default',true);
	$html = '<p>There are <a href="' . $url . '" title ="View finds for this
            constituency">' . $data  . ' finds</a> recorded in this constituency.</p>';
        return $html;
	} else {
	return false;
	}
	}
}

