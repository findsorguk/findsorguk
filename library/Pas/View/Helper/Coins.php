<?php
/**
 * A view helper for producing COINS metadata for Zotero
 * This might have come from inspiration at omeka....
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_CurUrl
 */
class Pas_View_Helper_Coins extends Zend_View_Helper_Abstract {
	/**
	 * Get the accessed date and time in correct format
	 */
	private function _Accessed(){
	$access = Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
	return $access;
	}

	/** Set up the constants for the metadata
	 *
	 * @var unknown_type
	 */
	const COINS_SPAN_CLASS 						= 'Z3988';
	const CTX_VER          						= 'Z39.88-2004';
	const RFT_VAL_FMT      						= 'info:ofi/fmt:kev:mtx:dc';
	const RFR_ID								= 'info:sid/finds.org.uk:generator';
	const ELEMENT_SET_DUBLIN_CORE				= 'Dublin Core';
	const ELEMENT_TITLE_DEFAULT					= '[unknown title]';
	const ELEMENT_DESCRIPTION_TRUNCATE_LENGTH	= 500;
	const ELEMENT_SUBJECT 						= 'Archaeology';
	const ELEMENT_LANGUAGE 						= 'English';
	const ELEMENT_RIGHTS 						= 'CC BY-SA';
	const ELEMENT_PUBLISHER 					= 'The Portable Antiquities Scheme';
	const ELEMENT_TYPE 							= 'WebPage';
	const ELEMENT_FORMAT 						= 'Text';

	/** Create the coinsdata array
	 *
	 * @var unknown_type
	 */
	private $_coinsData = array();

	/** Generate the metadata for insert
	 *
	 * @param string $title
	 * @param string $author
	 * @param date $published
	 * @param string $description
	 */
	public function Coins($title = NULL, $author = NULL,$published = NULL,$description = NULL)  {

	$description = substr($description, 0, self::ELEMENT_DESCRIPTION_TRUNCATE_LENGTH);

	//Create the array for the data
	$this->_coinsData['ctx_ver']     = self::CTX_VER;
	$this->_coinsData['rft_val_fmt'] = self::RFT_VAL_FMT;
	$this->_coinsData['rfr_id']      = self::RFR_ID;
	$this->_coinsData['rft.title']  = $title . ' - Portable Antiquities Scheme';
	$this->_coinsData['rft.subject'] = self::ELEMENT_SUBJECT;
	$this->_coinsData['rft.language'] = self::ELEMENT_LANGUAGE;
	$this->_coinsData['rft.publisher'] = self::ELEMENT_PUBLISHER;
	$this->_coinsData['rft.creator'] = $author;
	$this->_coinsData['rft.creator'] = $author;
	$this->_coinsData['rft.rights'] = self::ELEMENT_RIGHTS;
	$this->_coinsData['rft.accessed'] = $this->_Accessed();
	$this->_coinsData['rft.type'] = self::ELEMENT_TYPE;
	$this->_coinsData['rft.format'] = self::ELEMENT_FORMAT;
	$this->_coinsData['rft.date'] = $published;
	$this->_coinsData['rft.identifier'] = $this->view->CurUrl();
	$this->_coinsData['rft.description'] = $description;
	//Return the HTML span by building the html query
	$coinsSpan = '<span class="';
    $coinsSpan .= self::COINS_SPAN_CLASS;
    $coinsSpan .= '" title="';
    $coinsSpan .= http_build_query($this->_coinsData, '','&amp;');
	$coinsSpan .= '"></span>';
	return $coinsSpan;
	}
}