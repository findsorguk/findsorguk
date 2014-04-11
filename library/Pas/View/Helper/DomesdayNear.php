<?php
/**
 *
 * @author dpett
 * @version
 */

/**
 * DomesdayNear helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_DomesdayNear
	extends Zend_View_Helper_Abstract {

	protected $_url = 'http://domesdaymap.co.uk/';

	protected $_baseurl = 'http://domesdaymap.co.uk/place/';

	protected $_domesday;

	protected $_cache;

	public function __construct(){
		$this->_domesday = new Pas_Service_Domesday_Place();
		$this->_cache = Zend_Registry::get('cache');
	}

	/**
	 *
	 */
	public function domesdayNear($lat, $lng, $radius) {
	if(!is_int($radius)){
		throw new Exception('Defined radius needs to be an integer');
	}
	$params = array('lat' => $lat, 'lng' => $lng, 'radius' => $radius);
	$key = md5($lat . $lng . $radius);
	$response = $this->getPlacesNear($params,$key);

	return $this->buildHtml($response, $radius);
	}

	public function getPlacesNear(array $params, $key ){
		if (!($this->_cache->test($key))) {
		$data = $this->_domesday->getData('placesnear', $params);
		$this->_cache->save($data);
		} else {
		$data = $this->_cache->load($key);
		}
		return $data;
	}

	public function buildHtml($response, $radius){

	if($response){
	$html = '<h3>Adjacent Domesday Book places</h3>';
	$html .= '<a  href="' . $this->_url . '"><img class="dec flow" src="http://domesdaymap.co.uk/media/images/lion1.gif" width="67" height="93"/></a>';
	$html .= '<ul>';
	foreach($response as $domesday){
		$html .= '<li><a href="' . $this->_baseurl . $domesday->grid . '/' . $domesday->vill_slug
		. '">'. $domesday->vill . '</a></li>';
	}
	$html .= '</ul>';
	$html .= '<p>Domesday data  within ' . $radius . ' km of discovery point is surfaced via the excellent <a href="http://domesdaymap.co.uk">
	Open Domesday</a> website.</p>';
	return $html;
	}
	}

}

