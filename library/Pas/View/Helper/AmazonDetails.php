<?php
/**
 *
 * @author Daniel Pett
 * @version 1
 */

/**
 * AmazonDetails helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_AmazonDetails {

	protected $_config;

	protected $_cache;

	protected $_amazon;

	/** Construct the object
	 *
	 */
	public function __construct(){
		$this->_cache = Zend_Registry::get('cache');
		$this->_config = Zend_Registry::get('config');
		$this->_amazon = $this->_config->webservice->amazon->toArray();
	}

	/** Generate the amazon data call using ISBN number
	 * @param string $isbn
	 */
	public function amazonDetails($isbn) {
	if(!is_null($isbn) && is_string($isbn) && strlen($isbn) < 11){
	return $this->getAmazonData($isbn);
	}
	}

	/** Get the amazon data using Zend Service Amazon
	 * Remember that calls now need the associate tag
	 * @param string $isbn
	 */
	protected function getAmazonData($isbn){
	$key = md5($isbn);
	if (!($this->_cache->test($key))) {
	$amazon = new Zend_Service_Amazon($this->_amazon['apikey'],$this->_amazon['country'],$this->_amazon['secretkey']);

	$book = $amazon->itemLookup($isbn,array('AssociateTag' => $this->_amazon['AssociateTag'], 'ResponseGroup' => 'Large'));

	$this->_cache->save($book);
	} else {
	$book = $this->_cache->load($key);
	}


	return $this->parseData($book);
	}

	/** Parse the response
	 *
	 * @param object $book Amazon response object
	 */
	protected function parseData($book){
	if(is_object($book)){
		return $this->buildHtml($book);
	} else {
		return false;
	}
	}

	/** Build the HTML for rendering
	 *
	 * @param object $book
	 */
	protected function buildHtml($book){
	$html = '<div><h3>Amazon Book Data</h3><ul>';
	if(array_key_exists('MediumImage',$book) && (!is_null($book->MediumImage))){
	$html .= '<img class="flow" src="' . $book->MediumImage->Url . '" alt="Cover image for ' . $book->Title . '" height="'
	. $book->MediumImage->Height . '" width="' . $book->MediumImage->Width . '" class="amazonpicture" />';
	}
	$html .= '<li><a href="' . $book->DetailPageURL . '" title="View full details at Amazon"> ' . $book->Title
	. '</a></li> ';
	$html .= '<li>Number of pages: ' . $book->NumberOfPages . '</li>';
	$html .= '<li>Total new copies available: ' . $book->Offers->TotalNew . '</li>';
	$html .= '<li>Total used copies available: ' . $book->Offers->TotalUsed . '</li>';
	if(array_key_exists('FormattedPrice',$book)) {
	$html .= '<li>Price for new copy: ' . $book->FormattedPrice . '</li>';
	}
	$html .= '<li>Current sales rank at Amazon: ' . $book->SalesRank . '</li>';
	$html .= '<li>Binding type: ' . $book->Binding . '</li>';
	$html .= '<li>Publisher: ' . $book->Publisher . '</li>';
	$html .= '<li>Original publication date: ' . $book->PublicationDate . '</li>';
	if(array_key_exists('Author',$book)) {
	if(!is_array($book->Author)){
	$html .=  '<li>Author: ' . $book->Author . '</li>';
	} else {
		foreach($book->Author as $A => $v){
			$html .= '<li>Author: ' . $v . '</li>';
		}
	}
		}
	if(array_key_exists('EditorialReviews', $book)){
	$html .= '</ul>';
	$html .= '<h3>Amazon editoral review</h3>';
	foreach($book->EditorialReviews as $review){
		$html .= '<p>' . $review->Content . '</p>';
	}
	}
	if($book->SimilarProducts){
	$html .= '<h3>Similar books</h3>';
	$html .= '<ul>';
	foreach ($book->SimilarProducts AS $sim) {
	$html .= "<li>{$sim->Title}</li>";
	}
	}
	$html .= '</ul>';
	$html .= '</div>';
	return $html;
	}
}

