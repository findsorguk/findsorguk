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

    protected $_access;

    protected $_coinsData = array();

    public $_author = 'The Portable Antiquities Scheme';

    public $_title = 'A webpage from the Portable Antiquities Scheme';

    public $_published;

    const COINS_SPAN_CLASS = 'Z3988';
    const CTX_VER = 'Z39.88-2004';
    const RFT_VAL_FMT = 'info:ofi/fmt:kev:mtx:dc';
    const RFR_ID = 'info:sid/finds.org.uk:generator';
    const ELEMENT_SET_DUBLIN_CORE = 'Dublin Core';
    const ELEMENT_TITLE_DEFAULT	= '[unknown title]';
    const ELEMENT_DESCRIPTION_TRUNCATE_LENGTH	= 500;
    const ELEMENT_SUBJECT  = 'Archaeology';
    const ELEMENT_LANGUAGE  = 'English';
    const ELEMENT_RIGHTS  = 'CC BY-SA';
    const ELEMENT_PUBLISHER = 'The Portable Antiquities Scheme';
    const ELEMENT_TYPE = 'WebPage';
    const ELEMENT_FORMAT = 'Text';

    /** Get the accessed date
     *
     * @return \Pas_View_Helper_Coins
     */

    public function getAccessed(){
        $this->_access = Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
        $this->_published = $this->_access;
        return $this;
    }

    /** Set the title for the coins object
     *
     * @param string $title
     * @return \Pas_View_Helper_Coins
     */
    public function setTitle( $title ) {
        $this->_title = $title;
        return $this;
    }

    /** Set the author
     *
     * @param string $author
     * @return \Pas_View_Helper_Coins
     */
    public function setAuthor( $author ){
        $this->_author = $author;
        return $this;
    }

    /** Set the date published
     *
     * @param type $published
     * @return \Pas_View_Helper_Coins
     */
    public function setPublished( $published ) {
        $this->_published = $published;
        return $this;
    }

    /** Set the description
     *
     * @param type $description
     * @return \Pas_View_Helper_Coins
     */
    public function setDescription( $description ){
        $this->_description = substr($description, 0,
                self::ELEMENT_DESCRIPTION_TRUNCATE_LENGTH);
        return $this;
    }

    /** The coins object
     *
     * @return \Pas_View_Helper_Coins
     */
    public function coins() {
        return $this;
    }

    public function __toString() {
        return $this->html();
    }

    /** return the html after using the array of data
     *
     * @return string
     */
    public function html() {
        $this->_coinsData['ctx_ver']     = self::CTX_VER;
	$this->_coinsData['rft_val_fmt'] = self::RFT_VAL_FMT;
	$this->_coinsData['rfr_id']      = self::RFR_ID;
	$this->_coinsData['rft.title']  = $this->view->title($this->view->headTitle());
	$this->_coinsData['rft.subject'] = self::ELEMENT_SUBJECT;
	$this->_coinsData['rft.language'] = self::ELEMENT_LANGUAGE;
	$this->_coinsData['rft.publisher'] = self::ELEMENT_PUBLISHER;
	$this->_coinsData['rft.creator'] = $this->_author;
	$this->_coinsData['rft.creator'] = $this->_author;
	$this->_coinsData['rft.rights'] = self::ELEMENT_RIGHTS;
	$this->_coinsData['rft.accessed'] = $this->_accessed();
	$this->_coinsData['rft.type'] = self::ELEMENT_TYPE;
	$this->_coinsData['rft.format'] = self::ELEMENT_FORMAT;
	$this->_coinsData['rft.date'] = $this->_published;
	$this->_coinsData['rft.identifier'] = $this->view->curUrl();
	$this->_coinsData['rft.description'] = $this->_description;
	//Return the HTML span by building the html query
	$html = '<span class="';
        $html .= self::COINS_SPAN_CLASS;
        $html .= '" title="';
        $html .= http_build_query($this->_coinsData, '','&amp;');
	$html .= '"></span>';
	return $html;
    }
}