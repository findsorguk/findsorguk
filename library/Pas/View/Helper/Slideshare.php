<?php
/**
 * A view helper for retrieving a slideshare set
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_Service_SlideShare
 */
class Pas_View_Helper_Slideshare extends Zend_View_Helper_Abstract  {

	protected $_config, $_password, $_username, $_secret, $_key;

	public function __construct(){
	$this->_config = Zend_Registry::get('config');
	$this->_secret = $this->_config->webservice->slideshare->secret;
	$this->_key = $this->_config->webservice->slideshare->apikey;
	$this->_username = $this->_config->webservice->slideshare->username;
	$this->_password = $this->_config->webservice->slideshare->pword;
	}
	/** Build HTML response based on slideshare user ud
	 *
	 * @param string $ss_user
	 * @return string $html
	 */
	public function buildHtml($ss_user){
	if($ss_user) {
	$html = '<div class="row"><h3>Most recent presentations</h3>';
	$html .= '<ul class="thumbnails">';
	foreach($ss_user as $slideshow){
	$html .= '<li class="span2">';
	$html .= '<div class="thumbnail">';
	$html .= 'Title: ' . $slideshow->getTitle() . '<br />';
	$html .= '<a href="' . $slideshow->getPermaLink() . '" title="View this presentation at slideshare" >';
	$html .= '<img class="img-rounded" src="' . $slideshow->getThumbnailUrl() . '" alt="Thumbnail of presentation entitled'
	. $slideshow->getTitle().'" />';
	$html .= '</a><br />';
	$html .= 'Views: '. $slideshow->getNumViews();
	$html .= '</div>';
	$html .= '</li>';
	}
	$html .= '</ul>';
	$html .= '</div>';
	return $html;
	}
	}

	/** Query slideshare for slideshare objects
	 *
	 * @param unknown_type $userid
	 * @uses Zend_Service_SlideShare
	 */
	public function Slideshare($userid = NULL) {
	if(isset($userid)) {
	$online = new OnlineAccounts();
	$ssid = $online->getSlideshare($userid);
	if(count($ssid)) {
	$ssidno = $ssid['0']['account'];
	$ss = new Zend_Service_SlideShare($this->_key, $this->_secret, $this->_username, $this->_password);

	$starting_offset = 0;
	$limit = 4;
	$ss_user = $ss->getSlideShowsByUserName($ssidno, $starting_offset, $limit);
	return $this->buildHtml($ss_user);
	}
	}
	}
}