<?php
/** A class for generating an export from solr
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $exporter = new Pas_Exporter_Generate();
 * $exporter->setFormat('kml');
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Exporter
 * @license http://URL name
 * @example /app/modules/database/controllers/AjaxController.php
 * 
 */
class Pas_Exporter_Generate {

    /** The user
     * @access protected
     * @var object
     */
    protected $_user;

    /** The datatime object
     * @access protected
     * @var type 
     */
    protected $_dateTime;

    /** The memory to use
     * @access public
     * @var string
     */
    protected $_memory;

    /** The results to use
     * @access protected
     * @var array
     */
    protected $_results;
    
    /** The hero fields
     * @access protected
     * @var array
     */
    protected $_heroFields = array();

    /** The kml fields
     * @access protected
     * @var type 
     */
    protected $_kmlFields = array(
        'id', 'old_findID', 'description',
        'gridref', 'fourFigure', 'longitude',
        'latitude', 'county', 'woeid',
        'district', 'parish','knownas',
        'thumbnail'
        );

    /** GIS fields
     * @access protected
     * @var array
     */
    protected $_gisFields = array();

    /** The higher level access array
     * @access protected
     * @var array
     */
    protected $_higher = array('admin','flos','fa','treasure');

    /** The intermediate array
     * @access protected
     * @var array
     */
    protected $_intermediate = array('hero','research');

    /** The lower level array
     * @access protected
     * @var array
     */
    protected $_lower = array('member');

    protected $_search;

    /** The formats available
     * @access protected
     * @var array
     */
    protected $_formats = array(
        'csv', 'kml', 'hero',
        'gis', 'report', 'nms'
        );

    /** The format
     * @access protected
     * @var string
     */
    protected $_format;

    /** The array of parameters
     * @access protected
     * @var array
     */
    protected $_params;

    /** The max rows to use
     * @access protected
     * @var integer
     */
    protected $_maxRows;

    /** The parameters to clean
     * @access protected
     * @var type 
     */
    protected $_uncleanParams = array('csrf','page','module','controller','action');

    public function __construct() {
        $user = new Pas_User_Details();
        $this->_user = $user->getPerson();
        $this->_dateTime = Zend_Date::now()->toString('yyyyMMddHHmmss');
        $backendOptions = array(
        'cache_dir' => APPLICATION_PATH . '/tmp'
        );
        $this->_memory = Zend_Memory::factory('File', $backendOptions);
        $params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        $this->_params = $this->_cleanParams($params);
        $this->_search = new Pas_Solr_Handler();
        $this->_search->setCore('beowulf');
    }

    /** Set the number of rows per export
     * @accees public
     * @param int $maxRows
     * @return \Pas_Exporter_Generate
     */
    public function setMaxRows($maxRows) {
        $this->_maxRows = $maxRows;
        return $this;
    }

    /** Clean up the paramters
     * @access public
     * @param array $params
     * @return array
     * @throws Pas_Exporter_Exception
     */
    public function _cleanParams(array $params){
        if(is_array($params)){
            foreach($params as $k => $v){
                if(in_array($k, $this->_uncleanParams)){
                    unset($params[$k]);
                }
            }
            $params['format'] = 'json';
            return $params;
        } else {
            throw new Pas_Exporter_Exception('The parameters must be an array');
        }
    }

    /** get the format to parse
     * @access public
     * @return string
     */
    public function getFormat() {
        return $this->_format;
    }

    /** set the format to parse
     * @access public
     * @param string $format
     * @throws Pas_Exporter_Exception
     */
    public function setFormat($format) {
        if(in_array($format, $this->_formats)){
            $this->_format = $format;
        } else {
            throw new Pas_Exporter_Exception('That format is not allowed');
        }
        return $this->_format;
    }

    /** Get the maximum number of rows
     * @access public
     * @return integer
     */
    public function getMaxRows() {
        return $this->_maxRows;
    }

    /** Create the output
     * @access protected
     * @param type $format
     * @return string
     */
    protected function _createOutput($format){
        $format = ucfirst(strtolower($format));
        $class = 'Pas_Exporter_' . $format;
        $output = new $class();
        return $output->create();
    }

    /** Execute the commands
     * @access public
     * @return type
     */
    public function execute(){
        return $this->_createOutput($this->_format);
    }
}