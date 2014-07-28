<?php
/**
 * A view helper to display the meta data for a page.
 * 
 * There is no to string function for this as everything is output in the view.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->metaBase()
 * ->setDescription($description)
 * ->setImage($image)
 * ->setSubject($subject)
 * ->setKeywords($keywords)
 * ->generate();
 * ?>
 * </code>
 * 
 * @category   Pas
 * @package    View_Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @example /app/views/scripts/partials/content/articleLead.phtml
 * @uses Pas_View_Helper_CurUrl
 * @uses Pas_View_Helper_Title
 * @uses Pas_View_Helper_HeadRdf
 * @uses Zend_View_Helper_HeadMeta
 * @author Daniel Pett <dpett at britishmuseum.org>
 */

class Pas_View_Helper_MetaBase extends Zend_View_Helper_Abstract {

    /** The default description if not set
     * @access protected
     * @var string
     */
    protected $_description = 'A page from the Portable Antiquities Scheme';

    /** The default subject if not set
     * @access protected
     * @var string
     */
    protected $_subject = 'archaeology';

    /** The default keywords if not set
     * @access protected
     * @var string
     */
    protected $_keywords = 'portable,antiquities,archaeology';

    /** The default publisher if not set
     * @access protected
     * @var string
     */
    protected $_publisher = 'The Portable Antiquities Scheme/ British Museum';

    /** The default sitename
     * @access protected
     * @var string
     */
    protected $_sitename = 'The Portable Antiquities Scheme';
    
    /** The default image string
     * Must be an absolute url
     * @access public
     * @var type 
     */
    protected $_image = 'http://finds.org.uk/assets/logos/pas.jpg';
    
    /** The default setting for image width
     * @access public
     * @var string
     */
    protected $_imageWidth = '160';
    
    /** The default image height
     * @access public
     * @var string
     */
    protected $_imageHeight = '160';
    
    /** get the image width
     * @access public
     * @return string
     */
    public function getImageWidth() {
        return $this->_imageWidth;
    }

    /** Get the image height
     * @access public
     * @return string
     */
    public function getImageHeight() {
        return $this->_imageHeight;
    }

    /** Set the image width
     * @access public
     * @param string $imageWidth
     * @return \Pas_View_Helper_MetaBase
     */
    public function setImageWidth($imageWidth) {
        $this->_imageWidth = $imageWidth;
        return $this;
    }

    /** Set the image height
     * @access public
     * @param string $imageHeight
     * @return \Pas_View_Helper_MetaBase
     */
    public function setImageHeight($imageHeight) {
        $this->_imageHeight = $imageHeight;
        return $this;
    }

    
    /** Get the image
     * @access public
     * @return string
     */
    public function getImage() {
        return $this->_image;
    }

    /** Set the image
     * @access public
     * @param string $image
     * @return \Pas_View_Helper_MetaBase
     */
    public function setImage( $image ) {
        $this->_image = $image;
        return $this;
    }

        /** Get the description
     * @access public
     * @return string
     */
    public function getDescription() {
        return $this->_description;
    }

    /** Get the subject
     * @access public
     * @return string
     */
    public function getSubject() {
        return $this->_subject;
    }

    /** Get the keywords
     * @access public
     * @return string
     */
    public function getKeywords() {
        return $this->_keywords;
    }

    /** Get the publisher
     * @access public
     * @return string
     */
    public function getPublisher() {
        return $this->_publisher;
    }

    /** Get the site name
     * @access public
     * @return string
     */
    public function getSitename() {
        return $this->_sitename;
    }

    /** Set the description
     * @access public
     * @param string $description
     * @return \Pas_View_Helper_MetaBase
     * @todo put in truncate function below
     */
    public function setDescription($description) {
        $this->_description = $description;
        return $this;
    }

    /** Set the subject
     * @access public
     * @param string $subject
     * @return \Pas_View_Helper_MetaBase
     */
    public function setSubject($subject) {
        $this->_subject = $subject;
        return $this;
    }

    /** Set the keywords
     * @access public
     * @param string $keywords
     * @return \Pas_View_Helper_MetaBase
     */
    public function setKeywords($keywords) {
        $this->_keywords = $keywords;
        return $this;
    }

    /** Get the expiry date for cache in browser
     * @access public
     * @return string
     */
    public function getExpiry() {
        $date = new Zend_Date();
        $date->add('72',Zend_Date::HOUR);
        return $date->get(Zend_Date::RFC_1123);
    }

    /** The function to return
     * @access public
     */
    public function metaBase() {
        return $this;
    }
    
    /** Generate the header data
     * @access public
     */
    public function generate() {
        $this->view->headMeta()
                ->appendHttpEquiv('expires',$this->getExpiry())
                ->appendHttpEquiv('Content-Type','text/html; charset=UTF-8')
                ->appendHttpEquiv('Content-Language', 'en-GB')
                ->appendHttpEquiv('imagetoolbar', 'no')
                ->headMeta($this->view->title(),'title')
                ->headMeta($this->view->curUrl(),'dc.identifier')
                ->headMeta($this->view->curUrl(),'og:url')
                ->headMeta($this->getKeywords(),'keywords')
                ->headMeta($this->getPublisher(),'dc.publisher')
                ->headMeta($this->getDescription(),'description')
                ->headMeta()->setProperty('dc.subject',strip_tags($this->getSubject()))
                ->headMeta()->setProperty('og:site_name',$this->getSitename())
                ->headMeta()->setProperty('twitter:card', 'summary')
                ->headMeta()->setProperty('twitter:site', '@findsorguk')
                ->headMeta()->setProperty('twitter:creator', '@findsorguk')
                ->headMeta()->setProperty('twitter:image:width', $this->getImageWidth())
                ->headMeta()->setProperty('twitter:image:height', $this->getImageHeight())
                ->headMeta()->setProperty('twitter:image:src', $this->getImage())
                ->headMeta()->setProperty('dc.rights','Creative Commons BY-SA')
                ->headMeta('CC BY-SA','dc.rights');
        $this->view->headRdf($this->view->curUrl(),'og:url');
        $this->view->headRdf($this->getDescription(),'og:description');
        $this->view->headRdf($this->getImage(),'og:image');
        $this->view->headRdf('non_profit','og:type');
        $this->view->headRdf($this->view->title(),'og:title');
        $this->view->headRdf($this->getSitename(),'og:site_name');
        $this->view->headRdf('688811070','fb:admins');
        $this->view->headRdf('166174266729252','fb:app_id');
        $this->view->headLink(array(
            'rel' => 'foaf:primaryTopic',
            'href' => $this->view->curUrl() . '#this',
            'APPEND'));
        $this->view->headLink(array(
            'rel' => 'canonical',
            'href' => $this->view->curUrl(),
            'APPEND'));
    }
}
