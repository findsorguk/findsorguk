<?php
/**
 * Produce the finds to image html, might become obsolete when the solr comes online
 * 
 * An example of use:
 * 
 * <code>
 * <?php 
 * echo $this->findToImage()->setId(1);
 * </code>
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @todo add logging for missing images
 * @todo add caching
 * @todo fix the !file exists bit, it is wrong!
 */
class Pas_View_Helper_FindToImage extends Zend_View_Helper_Abstract {
    
    /** Image id to query
     * @access public
     * @var int
     */
    protected $_id;
    
    /** Get the id
     * @access public
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the id to query
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_FindToImage
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_FindToImage
     */
    public function findToImage() {
        return $this;
    } 
    
    /** Get finds data from the model
     * @access public
     * @param type $id
     * @return type
     */
    public function getFindsData($id) {
        $finds = new Finds();
        return $finds->getImageToFind($id);
    }
    
    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        $html = '';
        $imageData = $this->getFindsData($this->getId());
        foreach ($imageData as $data) {
            if (!is_null($data['i'])) {
                $file = './images/thumbnails/'.$data['i'].'.jpg';
                if (file_exists($file)) {
                    list($w, $h, $type, $attr) = getimagesize($file);
    
                    $html .= '<a href="/';
                    $html .= $data['imagedir'];
                    $html .= 'medium/';
                    $html .= strtolower($data['f']);
                    $html .= '" rel="lightbox" title="Medium sized image of: ';
                    $html .= $data['old_findID'];
                    $html .= ' a ';
                    $html .= $data['broadperiod'];
                    $html .= ' ';
                    $html .= $data['objecttype'];
                    $html .= '"><img src="';
                    $html .= $this->view->baseUrl();
                    $html .= '/images/thumbnails/';
                    $html .= $data['i'];
                    $html .= '.jpg" class="tmb" width="';
                    $html .= $w;
                    $html .= '" height="';
                    $html .= $h;
                    $html .= '" alt="';
                    $html .= ucfirst($data['objecttype']);
                    $html .= '" rel="license" resource="http://creativecommons.org/licenses/by/2.0/"/></a>';
                    }  else {
                        $html .= '<p>Image unavailable.</p>';
                    }
                    }
        }
        return $html;
    }
}
