<?php
/**
 * Get details for an amazon product from the isbn number.
 * 
 * An example of use:
 * <code>
 * <?php
 * echo $this->amazonDetails()->setIsbn($isbn);
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since 16/5/2014
 * @license GNU
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @todo Split the amazon html generator into chunks
 * @todo add validator for ISBN number
 */

class Pas_View_Helper_AmazonDetails extends Zend_View_Helper_Abstract
{

    /** The config object
     * @access protected
     * @var object
     */
    protected $_config;

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The amazon object
     * @access protected
     * @var object
     */
    protected $_amazon;

    /** The ISBN number to use
     * @access protected
     * @var string
     */
    protected $_isbn;

    /** Get the ISBN number
     * @access public
     * @return string
     */
    public function getIsbn() {
        return $this->_isbn;
    }

    /** Set the ISBN number
     * @access public
     * @param string $isbn
     * @return \Pas_View_Helper_AmazonDetails
     */
    public function setIsbn($isbn) {
        $this->_isbn = $isbn;
        return $this;
    }

    /** Get the cache object
     * @access public
     * @return object
     */
    public function getCache() {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Get the config object
     * @access public
     * @return \Zend_Config
     */
    public function getConfig() {
        $this->_config = Zend_Registry::get('config');
        return $this->_config;
    }

    /** Get the amazon object for query
     * @access public
     * @return array
     */
    public function getAmazon() {
        $this->_amazon = $this->getConfig()->webservice->amazon->toArray();
        return $this->_amazon;
    }

    /** Generate the amazon data call using ISBN number
     * @access public
     * @return \Pas_View_Helper_AmazonDetails
     */
    public function amazonDetails() {
    return $this;
    }

    /** Magic method to render html
     * @access public
     * @return string
     */
    public function __toString()  {
        return $this->getAmazonData();
    }

    /** Get the amazon data using Zend Service Amazon
    * Remember that calls now need the associate tag
    * @param string $isbn
    */
    protected function getAmazonData() {
        $isbn = $this->getIsbn();
        if (!is_null($isbn) && is_string($isbn) && strlen($isbn) < 11) {
            $key = md5($isbn);
            if (!($this->getCache->test($key))) {
            $amazonDetails = $this->getAmazon();
            $amazon = new Zend_Service_Amazon(
                    $amazonDetails['apikey'],
                    $amazonDetails['country'],
                    $amazonDetails['secretkey']
                    );

            $book = $amazon->itemLookup(
                    $isbn,
                    array(
                        'AssociateTag' => $amazonDetails['AssociateTag'],
                        'ResponseGroup' => 'Large'
                        )
                    );

            $this->getCache()->save($book);
            } else {
            $book = $this->getCache()->load($key);
            }
            return $this->parseData($book);
        }
    }

    /** Parse the response
     * @param object $book Amazon response object
     */
    protected function parseData($book)
    {
        if (is_object($book)) {
            return $this->buildHtml($book);
        } else {
            return false;
        }
    }

    /** Build the HTML for rendering
     * @access protected
     * @param  object $book
     * @return string $html
     */
    protected function buildHtml($book) {
    
        $html = '';
        $html .= '<div><h3>Amazon Book Data</h3><ul>';
        if (array_key_exists('MediumImage',$book) && 
                (!is_null($book->MediumImage))) {
                    $html .= '<img class="flow" src="';
                    $html .= $book->MediumImage->Url;
                    $html .= '" alt="Cover image for ';
                    $html .= $book->Title;
                    $html .= '" height="';
                    $html .= $book->MediumImage->Height;
                    $html .= '" width="';
                    $html .= $book->MediumImage->Width;
                    $html .= '" class="amazonpicture" />';
                }
        
                $html .= '<li><a href="';
                $html .= $book->DetailPageURL;
                $html .= '" title="View full details at Amazon"> ';
                $html .= $book->Title;
                $html .= '</a></li> ';
                $html .= '<li>Number of pages: ';
                $html .= $book->NumberOfPages;
                $html .= '</li><li>Total new copies available: ';
                $html .= $book->Offers->TotalNew;
                $html .= '</li><li>Total used copies available: ';
                $html .= $book->Offers->TotalUsed;
                $html .= '</li>';
    
                if (array_key_exists('FormattedPrice',$book)) {
                    $html .= '<li>Price for new copy: ';
                    $html .= $book->FormattedPrice;
                    $html .= '</li>';
                }
    
                $html .= '<li>Current sales rank at Amazon: ';
                $html .= $book->SalesRank;
                $html .= '</li>';
                $html .= '<li>Binding type: ';
                $html .= $book->Binding;
                $html .= '</li><li>Publisher: ';
                $html .= $book->Publisher;
                $html .= '</li><li>Original publication date: ';
                $html .= $book->PublicationDate;
                $html .= '</li>';
    
                if (array_key_exists('Author',$book)) {
                    if (!is_array($book->Author)) {
                        $html .= '<li>Author: ';
                        $html .= $book->Author;
                        $html .= '</li>';
                        } else {
                            foreach ($book->Author as $A => $v) {
                                $html .= '<li>Author: ' . $v . '</li>';
                            }
                            }
                            }
    
                            if (array_key_exists('EditorialReviews', $book)) {
                                $html .= '</ul>';
                                $html .= '<h3>Amazon editoral review</h3>';
                                foreach ($book->EditorialReviews as $review) {
                                    $html .= '<p>' . $review->Content . '</p>';
                                }
                                }
    
                                if ($book->SimilarProducts) {
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
