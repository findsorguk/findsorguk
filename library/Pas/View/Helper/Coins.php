<?php
/**
 * A view helper for producing COINS metadata for Zotero
 * 
 * This took some inspiration from omeka and was rewritten for use with this 
 * project.
 * 
 * A full example of use is given below:
 * 
 * <code>
 * <?php
 * echo $this->coins()
 * ->setTitle($title)
 * ->setAuthor($author)
 * ->setDescription($description)
 * ->setLanguage($language)
 * ->setSubject($subject)
 * ->setRights($rights)
 * ->setPublisher($publisher)
 * ->setType($type)
 * ->setFormat($format)
 * ->setPublished($published);
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_CurUrl
 * @author Daniel Pett <dpett at britishmuseum.org>
 */
class Pas_View_Helper_Coins extends Zend_View_Helper_Abstract {
    
    /** The accessed date
     * @access protected
     * @var string
     */
    protected $_accessed;

    /** The coins meta data array
     * @access protected
     * @var type 
     */
    protected $_coinsData = array();

    /** The author of the page, defaults to PAS
     * @access protected
     * @var string
     */
    protected $_author = 'The Portable Antiquities Scheme';

    /** The page title - default below
     * @access protected
     * @var string
     */
    protected $_title = 'A webpage from the Portable Antiquities Scheme';

    /** The date of publication
     * @access protected
     * @var string
     */
    protected $_published;
    
    /** The description of the page - default set
     * @access protected
     * @var string 
     */
    protected $_description = 'A page from the Portable Antiquities Scheme';
    
    /** The css class to use
     * @access protected
     * @var string
     */
    protected $_coinsSpanClass = 'Z3988';
    
    /** Version number
     * 
     */
    const CTX_VER = 'Z39.88-2004';
    
    /** Metadata standard
     * 
     */
    const RFT_VAL_FMT = 'info:ofi/fmt:kev:mtx:dc';
    
    /** generator ID
     * 
     */
    const RFR_ID = 'info:sid/finds.org.uk:generator';
    
    /** Dublin core metadata
     * 
     */
    const ELEMENT_SET_DUBLIN_CORE = 'Dublin Core';
    
    /** The truncation level
     * @access protected
     * @var int
     */
    protected $_truncate = 500;
    
    /** Rights statement
     * @access protected
     * @var string
     */
    protected $_rights = 'CC BY';
    
    /** Language of choice - English by default
     * @access protected
     * @var string
     */
    protected $_language = 'English';
    
    /** The subject of the page - default to archaeology
     * @access protected
     * @var string The subject of the page or resource
     */
    protected $_subject = 'Archaeology';
    
    /** The publisher of the page - defaults to BM
     * @access protected
     * @var string
     */
    protected $_publisher = 'The British Museum';
   
    /** The type of page - default webpage
     * @access protected
     * @var string
     */
    protected $_type = 'WebPage';
    
    /** The format of the page = default text
     * @access public
     * @var string
     */
    protected $_format = 'Text';
    
    /** Get the truncate level
     * @access public
     * @return int The level of truncation, the default is 500 chars;
     */
    public function getTruncate() {
        return $this->_truncate;
    }

    /** Set the truncate level for descriptive text
     *  @access public
     * @param int $truncate
     * @return \Pas_View_Helper_Coins
     */
    public function setTruncate($truncate) {
        $this->_truncate = $truncate;
        return $this;
    }

    /** Get the css class for use
     * @access public
     * @return string A class used for the css 
     */
    public function getCoinsSpanClass() {
        return $this->_coinsSpanClass;
    }

    /** Set a new class for the coins span
     * @access public
     * @param string $coinsSpanClass
     * @return \Pas_View_Helper_Coins
     */
    public function setCoinsSpanClass($coinsSpanClass) {
        $this->_coinsSpanClass = $coinsSpanClass;
        return $this;
    }

    /** Get the rights for the page
     * @access public
     * @return string The rights attached to the page
     */
    public function getRights() {
        return $this->_rights;
    }

    /** Set the rights for a page
     * @access public
     * @param string $rights
     * @return \Pas_View_Helper_Coins
     */
    public function setRights($rights) {
        $this->_rights = $rights;
        return $this;
    }
    
    /** Get the language of the page
     * @access public
     * @return string
     */
    public function getLanguage() {
        return $this->_language;
    }

    /** Set the language of the page
     * @access public
     * @param string $language The language you want to set
     * @return \Pas_View_Helper_Coins
     */
    public function setLanguage($language) {
        $this->_language = $language;
        return $this;
    }
    
    /** Get the subject of the page
     * @access public
     * @return string The subject of the page
     */
    public function getSubject() {
        return $this->_subject;
    }

    /** Set the page subject
     * @access public
     * @param string $subject
     * @return \Pas_View_Helper_Coins
     */
    public function setSubject($subject) {
        $this->_subject = $subject;
        return $this;
    }
    
    /** Get the publisher of the page
     * @access public
     * @return string The publisher
     */
    public function getPublisher() {
        return $this->_publisher;
    }

    /** Set a publisher for the page
     * @access public
     * @param string $publisher
     * @return \Pas_View_Helper_Coins
     */
    public function setPublisher($publisher) {
        $this->_publisher = $publisher;
        return $this;
    }
    
    /** Get the page type
     * @access public
     * @return string The page type
     */
    public function getType() {
        return $this->_type;
    }
    
    /** Set the page type
     * @access public
     * @param string $type
     * @return \Pas_View_Helper_Coins
     */
    public function setType($type) {
        $this->_type = $type;
        return $this;
    }
    
    /** Get the format of the page
     * @access public
     * @return string Page format
     */
    public function getFormat() {
        return $this->_format;
    }

    /** Set the format of the page
     * @access public
     * @param string $format Page format
     * @return \Pas_View_Helper_Coins
     */
    public function setFormat($format) {
        $this->_format = $format;
        return $this;
    }

    /** Get the date of access for the page
     * @access public
     * @return string
     */
    public function getAccessed() {
        $this->_accessed = Zend_Date::now()->toString('yyyy-MM-dd HH:mm');
        return $this->_accessed;
    }
    
    /** Get the title to use
     * @access public
     * @return string The page title to send to Zotero
     */
    public function getTitle() {
        return $this->_title;
    }
    
    /** Set the page title
     * @access public
     * @param string $title
     * @return \Pas_View_Helper_Coins
     */
    public function setTitle($title)  {
        $this->_title = $title;
        return $this;
    }

    /** Get the page's author
     * @access public
     * @return string The author's name
     */
    public function getAuthor() {
        return $this->_author;
    }
    
    /** Set the page's author
     * @access public
     * @param string $author
     * @return \Pas_View_Helper_Coins
     */
    public function setAuthor($author) {
        $this->_author = $author;
        return $this;
    }

    /** Set the published date
     * @access public
     * @param string $published
     * @return \Pas_View_Helper_Coins
     */
    public function setPublished($published) {
        $this->_published = $published;
        return $this;
    }

    /** Get the date published
     * @access public
     * @return string
     */
    public function getPublished() {
        return $this->_published;
    }
    
    /** Get the description of the webpage and truncate
     * @access public
     * @param string $description
     * @return \Pas_View_Helper_Coins
     */
    public function setDescription($description) {
        $this->_description = substr(strip_tags($description), 0, 
                $this->getTruncate());
        return $this;
    }
    
    /** Get the description of the webpage
     * @access public
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }
    
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_Coins
     */
    public function coins()  {
        return $this;
    }

    /** The to string function
     * @access public
     * @return string The html for the Zotero span
     */
    public function __toString() {
        return $this->getCoinData();
    }

   /** Return the span for rendering on the page
    * @access public
    * @return string The Zotero COINS metadata
    */
    public function getCoinData() {
        $this->_coinsData['ctx_ver']     = self::CTX_VER;
        $this->_coinsData['rft_val_fmt'] = self::RFT_VAL_FMT;
        $this->_coinsData['rfr_id']      = self::RFR_ID;
        $this->_coinsData['rft.title']  = $this->getTitle();
        $this->_coinsData['rft.subject'] = $this->getSubject();
        $this->_coinsData['rft.language'] = $this->getLanguage();
        $this->_coinsData['rft.publisher'] = $this->getPublisher();
        $this->_coinsData['rft.creator'] = $this->getAuthor();
        $this->_coinsData['rft.rights'] = $this->getRights();
        $this->_coinsData['rft.accessed'] = $this->getAccessed();
        $this->_coinsData['rft.type'] = $this->getType();
        $this->_coinsData['rft.format'] = $this->getFormat();
        $this->_coinsData['rft.date'] = $this->getPublished();
        $this->_coinsData['rft.identifier'] = $this->view->curUrl();
        $this->_coinsData['rft.description'] = $this->getDescription();
        //Return the HTML span by building the html query
    
        $html = '<span class="';
        $html .= $this->getCoinsSpanClass();
        $html .= '" title="';
        $html .= http_build_query($this->_coinsData, '','&amp;');
        $html .= '"></span>';
        return $html;
    }
}
