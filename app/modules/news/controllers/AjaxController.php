<?php
/**
 * A controller for ajax based functions for news module
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 2
 * @since version 1
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Param_Exception
 * @uses Finds
 * @uses Zend_Http_Client
 * @uses Events
 *
 */
class News_AjaxController extends Pas_Controller_Action_Admin {

    /** The they work for you api key
     * @access protected
     * @var string
     */
    protected $_apikey;

    /** Initialise the ACL and contexts
     * @access public
     * @return void
    */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->layout->disableLayout();
        $this->_apikey = $this->_helper->config()->webservice->twfy->apikey;
    }

    /** Index page, nothing happens here
     * @access public
     * @return void
     */
    public function indexAction() {
        //Dummy action
    }

    /** Curl function to retrieve data from url
     * @access public
     * @param string $url
     */
    public function get( $url ){
        $config = array(
            'adapter'   => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_POST =>  true,
                CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
            ),
	);
        $client = new Zend_Http_Client($url, $config);
	return $client->request();
    }

    /** Action for getting mapping data for the news module
     * @access public
     * @return void
     */
    public function newsdataAction() {
        $news = new News();
        $this->view->mapping = $news->getMapdata();
    }

    /** Return data for the event data ajax page
     * @access public
     * @return void
     */
    public function eventdataAction() {
	$events = new Events();
	$this->view->mapping = $events->getMapdata();
    }

    /** Find news by individual MP from theyworkforyou
     * @todo rewrite to use YQL
     * @access public
     * @return void
    */
    public function mpAction() {
	$id = $this->getParam('id');
	$twfy = 'http://www.theyworkforyou.com/api/getPerson?key=';
        $twfy .= $this->_apikey;
        $twfy .= '&id=';
        $twfy .= $id;
        $twfy .= '&output=js';
	$this->view->data = json_decode($this->get($twfy));
    }

    /** Get map data for news map
     * @access public
     * @return void
     * @throws Pas_Param_Exception
     */
    public function mapAction()	{
	if($this->getParam('constituency',false)){
            $finds = new Finds();
            $this->view->finds = $finds->getFindsConstituencyMap($this->getParam('constituency'));
	} else {
            throw new Pas_Param_Exception($this->_missingParameter);
	}
    }
}