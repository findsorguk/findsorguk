<?php
/**
 * A view helper for displaying social bookmarks
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_Title
 * @uses Pas_View_Helper_CurUrl
 * @uses Zend_View_Helper_Baseurl
 */
class Pas_View_Helper_Socialbookmarking extends Zend_View_Helper_Abstract  {

	/** Create a list of social bookmarks from the database entered list
	 * @return string $html
	 *
	 */
	public function socialbookmarking() {
        $html = '<div id="bookmarks" class="removePrint"><p>Social Bookmarking: ';
	$social = new Bookmarks();
	$books = $social->getValidBookmarks();
	foreach ($books as $service) {
		$targetHref = str_replace('{title}', $this->view->title(), $service['url']);
		$targetHref = str_replace('{link}', $this->view->CurUrl(), $targetHref);
		$image = $service['image'];
		list($w,$h) = getimagesize($this->view->baseUrl() . 'images/social/' . $image);
        $serviceIcon = '<a class="social-img" href="' . $this->view->escape($targetHref)
        . '" title="Share this page on ' . $service['service'] . '"><img src="'
        . $this->view->baseUrl() .'/images/social/' . $image . '" alt="Favicon for '
        . $service['service'] . '" width="' . $w . '" height="' . $h . '"/></a>';
        $html .= $serviceIcon;
	}
//        $this->view->inlineScript()->appendFile('/js/plusone.js', $type='text/javascript');
//	$html .= '<g:plusone size="small"></g:plusone>';
	$html .= '</p></div>';
	return $html;
        }
}