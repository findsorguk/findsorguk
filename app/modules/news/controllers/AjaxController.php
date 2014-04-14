<?php
/** Controller for ajax information gathering of news
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class News_AjaxController extends Pas_Controller_Action_Admin {

	protected $_apikey;
	
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
		$this->_helper->_acl->allow(NULL);
		$this->_helper->layout->disableLayout();
		$this->_apikey = $this->_helper->config()->webservice->twfy->apikey;  
    }

    /** Index page, nothing happens here
	*/ 
	public function indexAction() {
	}
	
	/** Curl function
	* @param $url The URL to fetch
	* @todo rewrite this and make ZEND_HTTP
	*/ 
	public function get($url){
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$output = curl_exec($ch); 
	curl_close($ch);
	return $output;
	}

	/** get map data for news map
	*/ 
	public function newsdataAction() {
	$news = new News();
	$this->view->mapping = $news->getMapdata();
	}

	/** Find news by individual MP from theyworkforyou
	* @todo rewrite to use YQL
	*/ 
	public function mpAction() {
	$id = $this->_getParam('id');
	$twfy = 'http://www.theyworkforyou.com/api/getPerson?key=' . $this->_apikey . '&id=' . $id 
	. '&output=js';
	$data = json_decode($this->get($twfy));
	$this->view->data = $data;	
	}

	/** get map data for news map
	*/ 
	public function mapAction()	{
	if($this->_getParam('constituency',false)){
	$finds = new Finds();
	$finds = $finds->getFindsConstituencyMap($this->_getParam('constituency'));
	$dom = new DOMDocument("1.0");
	$node = $dom->createElement("markers");
	$parnode = $dom->appendChild($node); 
	foreach($finds as $mapdata) {
	$restricted = array('public','member');
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$user = $auth->getIdentity();
	$geo = new Pas_Geo_Gridcalc($mapdata['fourFigure']);
	$results = $geo->convert();
	if(!in_array($user->role,$restricted))  {
	$lat = $mapdata['declat'];
	$long = $mapdata['declong']; 
	} else {
		$geo = new Pas_Geo_Gridcalc($mapdata['fourFigure']);
	$results = $geo->convert();
	$lat = $results['decimalLatLon']['decimalLatitude'];
	$long = $results['decimalLatLon']['decimalLongitude']; 
	} 
	} else {
		$geo = new Pas_Geo_Gridcalc($mapdata['fourFigure']);
	$results = $geo->convert();
	$lat = $results['decimalLatLon']['decimalLatitude'];
	$long = $results['decimalLatLon']['decimalLongitude'];  
	}

	 $html = '';
	  if(isset($mapdata['i'])) {
	  $html .=  '<div id="tmb">'
	  .'<img src="http://'
	  . $_SERVER['SERVER_NAME']
	  . '/images/thumbnails/'
	  . $mapdata['i']
	  .'.jpg"/></div>';
	  }

	  $html .= '<div id="detailsmap"><p>'
	  . ucwords(strtolower($mapdata['county']))
	  . ' - <a href="http://'
	  . $_SERVER['SERVER_NAME']
	  . $this->view->url(array('module'=> 'database','controller' => 'artefacts','action' => 'record','id'=> $mapdata['id']),null,true)
	  . '" title="View record\'s details">'
	  . $mapdata['old_findID'] 
	  . '</a><br />' 
	  . $mapdata['objecttype']
	  .'<br />'
	  .$mapdata['broadperiod']
	  .'</p></div>';
	 
	  $node = $dom->createElement("marker");  
	  $newnode = $parnode->appendChild($node);  
	  $newnode->setAttribute("name", $html);
	  $newnode->setAttribute("broadperiod", $mapdata['broadperiod']);  
	  $newnode->setAttribute("lat", $lat);  
	  $newnode->setAttribute("lng", $long); 
	  $newnode->setAttribute("type", $mapdata['objecttype']); 
	  $newnode->setAttribute("workflow",
	  str_replace(array('1','2','3','4'),
	  array('quarantine','review','published','validation'),
	  $mapdata['secwfstage']))
	  ; 
 
	} 
	header('Content-Type: text/xml');
	echo $dom->saveXML();
	} else {
		throw new Exception($this->_missingParameter);
	}
		
	}

}