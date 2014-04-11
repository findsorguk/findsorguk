<?php
/**
 * A view helper for displaying image of a MP or Lord from theyworkforyou
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @todo Change the curl method to zend_http?
 * @todo change to YQL tables?
 * @todo change the view helper to pass in key as a parameter in the construct function?
 * @author Daniel Pett
 * @since September 13 2011
 * @see http://www.theyworkforyou.com/api/ for documentation of their api.
 */
class Pas_View_Helper_TwfyImage extends Zend_View_Helper_Abstract {

	protected $_cache;
	protected $_twfykey;
	protected $_config;

	/**Construct the cache, config and retrieve the api key
	 *
	 */
	public function __construct(){
	$this->_cache = Zend_Registry::get('rulercache');
	$this->_config = Zend_Registry::get('config');
	$this->_twfykey = $this->_config->webservice->twfy->apikey;
	}
	/** Retrieve the URL's content via curl
	*
	* @param string $url
	*/
	public function get($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
	}

	/** Call the they work for you api, caches response
	 *
	 * @param integer $id The MP or Lord's ID number
	 */

	public function callapi($id) {
	if (!($this->_cache->test('mptwfy'.$id))) {
	$twfy = 'http://www.theyworkforyou.com/api/getPerson?key='
	. $this->_twfykey . '&id=' . $id . '&output=js';
	$data = json_decode($this->get($twfy));
	$this->_cache->save($data);
	} else {
	$data = $this->_cache->load('mptwfy'.$id);
	}
	return $data;
	}

	/** Create the image
	 *
	 * @param integer $id The MP or Lord's ID number
	 */
	public function twfyImage($id = NULL){
	if(isset($id)) {
	$data = $this->callapi($id);
        return $this->buildHtml($data);
	} else {
	return false;
	}
	}

	/** Build the HTML for return
	 * @param array $data
	 *
	 */
	public function buildHtml($data) {
        if(!is_null($data['0']->image)){

	$html = '<img src="http://www.theyworkforyou.com/';
        $html .= $data['0']->image . '" class="flow" alt="Profile picture for ';
        $html .= $data['0']->full_name . '" height="' . $data['0']->image_height;
        $html .= '" width="' . $data['0']->image_width . '"/>';
        }

	return $html;
	}
}
