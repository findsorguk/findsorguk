<?php
/**
 * AmazonDataDumpResearch helper
 * @author Daniel Pett <dpett @britishmuseum.org>
 * @license http://URL GNU
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @since 15/5/2014
 * @uses Zend_Registry
 * @uses Zend_Service_Amazon_S3
 * @uses viewHelper Pas_View_Helper
 * @uses Zend_View_Helper_PartialLoop
 */
class Pas_View_Helper_AmazonDataDumpResearch extends Zend_View_Helper_Abstract
{

    /** The S3 Object
     * @access protected
     * @var object
     */
    protected $_S3;

    /** The AWS key
     * @access protected
     * @var string
     */
    protected $_awsKey;

    /** The AWS Secret
     * @access protected
     * @var string
     */
    protected $_awsSecret;

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The cache key to use
     * @access protected
     * @var string
     */
    protected $_cacheKey = 'amazonresearchData';

    /** The AWS bucket to query
     * @access protected
     * @var string
     */
    protected $_bucket = 'findsorguk-research/';

    /** Get the AWS bucket
     * @access public
     * @return string
     */
    public function getBucket()
    {
        return $this->_bucket;
    }

    /** Set a different bucket to the default
     * @access public
     * @param  type                            $bucket
     * @return \Pas_View_Helper_AmazonDataDump
     */
    public function setBucket($bucket)
    {
        $this->_bucket = $bucket;

        return $this;
    }

    /** Get the default cache key
     * @access public
     * @return string
     */
    public function getCacheKey()
    {
        return $this->_cacheKey;
    }

    /** Set a different cache key
     * @access public
     * @param  type                            $cacheKey
     * @return \Pas_View_Helper_AmazonDataDump
     */
    public function setCacheKey($cacheKey)
    {
        $this->_cacheKey = $cacheKey;

        return $this;
    }

    /** Get the S3 object
     * @access public
     * @return object
     */
    public function getS3()
    {
        $this->_S3 = new Zend_Service_Amazon_S3($this->getAwsKey(),
                $this->getAwsSecret());

        return $this->_S3;
    }

    /** Get the AWS key
     * @access public
     * @return string
     */
    public function getAwsKey()
    {
        $this->_awsKey = $this->getConfig()->webservice->amazonS3->accesskey;

        return $this->_awsKey;
    }

    /** Get the AWS secret
     * @access public
     * @return string
     */
    public function getAwsSecret()
    {
        $this->_awsSecret = $this->getConfig()->webservice->amazonS3->secretkey;

        return $this->_awsSecret;
    }

    /** Get the config object
     * @access public
     * @return object
     */
    public function getConfig()
    {
        $this->_config = Zend_Registry::get('config');

        return $this->_config;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('rulercache');

        return $this->_cache;
    }

    /** The role
     *
     * @var string
     */
    protected $_role;

    /** The allowed roles for this data
     * @access protected
     * @var array
     */
    protected $_roles = array(
        'admin', 'research', 'flos',
        'treasure', 'hero', 'fa'
        );

    /** Get the role of the user
     * @access public
     * @return string
     */
    public function getRole()
    {
        $user = new Pas_User_Details();
        $this->_role = $user->getPerson()->role;

        return $this->_role;
    }

    /** Construct the object
     *
     */
    public function __construct()
    {
    }

    /** Set up the class to return data
     * @access public
     * @return \Pas_View_Helper_AmazonDataDumpResearch
     */
    public function amazonDataDumpResearch()
    {
        return $this;
    }

    /** Get the data from Amazon
     * @access public
     * @return boolean | array
     */
    public function getData()
    {
        if (in_array($this->getRole(), $this->_roles)) {
    $key = md5( $this->getCacheKey() );
    if (!($this->getCache()->test($key))) {
            $list = $this->getS3()->getObjectsByBucket( $this->getBucket() );
            $data = array();
            foreach ($list as $name) {
                $data[] = array(
                    'filename' => $name,
                    'properties' => $this->getS3()->getInfo($this->getBucket()
                            . $name )
                    );
                }

                $this->getCache()->save($data);

            } else {
                $data = $this->getCache()->load($key);

            }

            return $this->_buildHtml($data);

            } else {
                return false;

            }
    }

    /** Return a string of data
     * @access public
     * @return type
     */
    public function __toString()
    {
        return $this->_buildHtml();
    }

    protected function _buildHtml()
    {
        $html = '<h2>Daily research data dumps of entire database</h2>';
    $html .= '<p>These data are licensed under CC-BY and includes the following fields and objects from workflow stages validation and published. Other stages are not available to your login; this dump is generated at 5am GMT daily.</p>';
        $html .= '<blockquote><p>';
        $html .= 'id, objecttype, broadperiod, periodFromName, periodToName, fromdate, todate, description, notes, workflow, materialTerm, secondaryMaterialTerm, , subsequentActionTerm, discoveryMethod, datefound1, datefound2, TID, rallyName, weight, height, diameter, thickness, length, quantity, finder, identifier, recorder, denominationName, rulerName, mintName, obverseDescription, obverseLegend, reverseDescription, reverseLegend, tribeName, reeceID, cciNumber, mintmark, abcType, categoryTerm, typeTerm, moneyerName, reverseType, regionName, county, district, parish, knownas, gridref, gridSource, fourFigure, easting, northing, latitude, longitude, geohash, coordinates, fourFigureLat, fourFigureLon';
        $html .= '</blockquote></p>';
        $data = $this->getData();
        $html .=$this->view->partialLoop('partials/admin/fileListResearch.phtml',
                $data);

        return $html;

    }
}
