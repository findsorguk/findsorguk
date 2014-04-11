<?php
/**
 * This class is to retrieve tweets and display them.
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_AutoLink
 * @uses Zend_View_Helper_Url
 * @author Daniel Pett
 * @since September 13 2011
*/
class Pas_View_Helper_SecretTreasureTweets
	extends Zend_View_Helper_Abstract {

	protected $_cache;

	protected $_config;

	/** Constructor
	 *
	 */
	public function __construct(){
	$this->_cache = Zend_Registry::get('cache');
	$this->_config = Zend_Registry::get('config');
	}



	/** Call Twitter after getting token for oauth
	 *
	 */
	public function callTwitter() {
	if (!($this->_cache->test('treasureTweets'))) {
	$twitter = new Zend_Service_Twitter_Search('json');
	$tweets = $twitter->search('#secrettreasure OR #secrettreasures', array('lang' => 'en','rpp' => 20));
    $this->_cache->save($tweets);
	} else {
	$tweets = $this->_cache->load('treasureTweets');
	}
	return $this->buildHtml($tweets);
	}

	/** Build the html
	 *
	 * @param array $response
	 */
	public function buildHtml($tweets){
	$html = '<div class="well"><h3>What\'s being said on Twitter?</h3>';
	$html .= '<ul>';
	foreach($tweets->results as $post){
	$html .= '<li>On <strong>'. date('m.d.y @ H:m:s',strtotime($post->created_at))
	. '</strong>, <strong><a href="http://www.twitter.com/'. $post->from_user
	. '">' . $post->from_user. '</a></strong> said: '. $this->view->autoLink($post->text)
	. '</li>';
	}
	$html .= '</ul></div>';
	return $html;
	}

	/** Call Twitter to get tweets
	 *
	 */
	public function secretTreasureTweets() {
	return $this->callTwitter();
	}


}

