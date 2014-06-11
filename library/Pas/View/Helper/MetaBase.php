<?php
/**
 * A view helper to display the meta data for a page
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @example /app/views/scripts/partials/content/articleLead.phtml
 */

class Pas_View_Helper_MetaBase extends Zend_View_Helper_Abstract {

    protected $_description = 'A page from the Portable Antiquities Scheme';

    protected $_subject = 'archaeology';

    protected $_keywords = 'portable,antiquities,archaeology';

    protected $_publisher = 'The Portable Antiquities Scheme/ British Museum';

    protected $_sitename = 'The Portable Antiquities Scheme';


    public function getDescription() {
        return $this->_description;
    }

    public function getSubject() {
        return $this->_subject;
    }

    public function getKeywords() {
        return $this->_keywords;
    }

    public function getPublisher() {
        return $this->_publisher;
    }

    public function getSitename() {
        return $this->_sitename;
    }

    /**
     * Set the description
     * @access public
     * @param type $description
     * @return \Pas_View_Helper_MetaBase
     * @todo put in truncate function below
     */
    public function setDescription($description) {
        $this->_description = $description;
        return $this;
    }

    public function setSubject($subject) {
        $this->_subject = $subject;
        return $this;
    }

    public function setKeywords($keywords) {
        $this->_keywords = $keywords;
        return $this;
    }

    public function getExpiry() {
        $date = new Zend_Date();
        $date->add('72',Zend_Date::HOUR);
        return $date->get(Zend_Date::RFC_1123);
    }

    public function metaBase() {
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
                ->headMeta()->setProperty('twitter:image:width', '160')
                ->headMeta()->setProperty('twitter:image:height', '160')
                ->headMeta()->setProperty('twitter:image:src', $this->getImage())
                ->headMeta()->setProperty('dc.rights','Creative Commons BY-SA')
                ->headMeta('CC BY-SA','dc.rights');

        $this->view->headRdf($this->view->curUrl(),'og:url');
        $this->view->headRdf($this->getDescription(),'og:description');

//    $this->view->headRdf('https://fbcdn-profile-a.akamaihd.net/hprofile-ak-prn1/c59.0.712.712/s160x160/540083_10151131686659762_1658645946_n.jpg','og:image');

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
