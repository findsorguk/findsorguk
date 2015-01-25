<?php
/**
 * AmazonDataDump helper
 * @author Daniel Pett <dpett @britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @since 15/5/2014
 * @uses Zend_Registry
 * @uses Zend_Service_Amazon_S3
 * @uses viewHelper Pas_View_Helper
 * @uses Zend_View_Helper_PartialLoop
 */
class Pas_View_Helper_AmazonDataDump extends Zend_View_Helper_Abstract
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
    protected $_cacheKey = 'researchamazon';

    /** The AWS bucket to query
     * @access protected
     * @var string
     */
    protected $_bucket = 'findsorguk';

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
        return $this->_S3;
    }

    /** Get the AWS key
     * @access public
     * @return string
     */
    public function getAwsKey()
    {
        return $this->_awsKey;
    }

    /** Get the AWS secret
     * @access public
     * @return string
     */
    public function getAwsSecret()
    {
        return $this->_awsSecret;
    }

    /** Get the config object
     * @access public
     * @return object
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /** Set a new AWS key
     * @access public
     * @param  string                          $awsKey
     * @return \Pas_View_Helper_AmazonDataDump
     */
    public function setAwsKey($awsKey)
    {
        $this->_awsKey = $awsKey;

        return $this;
    }

    /** Set a different AWS secret
     * @access public
     * @param  type                            $awsSecret
     * @return \Pas_View_Helper_AmazonDataDump
     */
    public function setAwsSecret($awsSecret)
    {
        $this->_awsSecret = $awsSecret;

        return $this;
    }

    /** Construct the object
     *
     */
    public function __construct()
    {
        $this->_config = Zend_Registry::get('config');
        $this->_awsKey = $this->_config->webservice->amazonS3->accesskey;
        $this->_awsSecret = $this->_config->webservice->amazonS3->secretkey;
        $this->_S3 = new Zend_Service_Amazon_S3($this->_awsKey, $this->_awsSecret);
        $this->_cache = Zend_Registry::get('cache');
    }

    /** Set up the class
     * @access public
     * @return \Pas_View_Helper_AmazonDataDump
     */
    public function amazonDataDump()
    {
        return $this;
    }

    /** Build html for returning
     * @access protected
     * @return string
     */
    protected function _buildHtml()
    {
        $key = md5($this->getKey());
        if (!($this->getCache()->test($key))) {
            $list = $this->getS3()->getObjectsByBucket( $this->getBucket() );
            $data = array();
            foreach ($list as $name) {
                $data[] = array(
                    'filename' => $name,
                    'properties' => $this->getS3()->getInfo( "findsorguk/$name" )
                        );
            }
            $this->getCache()->save($data);
            } else {
                $data = $this->getCache()->load($key);
            }

        return $this->view->partialLoop( 'partials/admin/fileList.phtml', $data );
    }

    public function __toString()
    {
        return $this->_buildHtml();
    }

}
