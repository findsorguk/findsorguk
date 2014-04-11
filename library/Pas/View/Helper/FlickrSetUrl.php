<?php
/** A view helper for displaying the flickr set url
 * Really unsure why this is needed!
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @since 5 October 2011
 * @author Daniel Pett
 * @copyright DEJ PETT
 * @license GNU
 */
class Pas_View_Helper_FlickrSetUrl 
	extends Zend_View_Helper_Abstract {
	
	/** Create and return the url
	 * @param int $id the Id of the photoset
	 * @return string
	 */
	public function FlickrSetUrl( $id ) {
	return "http://flickr.com/photos/finds/sets/{$id}/";
	}
	

}