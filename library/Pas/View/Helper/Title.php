<?php
/**
 * A view helper for displaying page title without the <title></title> tags
 * 
 * An example of use:
 * 
 * <code>
 * <h2>
 * <?php 
 * echo $this->title();
 * ?>
 * </h2>
 * </code>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_HeadTitle
 * @author Daniel Pett <dpett at britishmuseum.org>
 */
class Pas_View_Helper_Title extends Zend_View_Helper_Abstract {
    
    /** The default title string if none set
     * @access protected
     * @var string
     */
    protected $_title = 'The Portable Antiquities Scheme';

    /** Magic method
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getTitle();
    }

    /** Get the title
     * @access public
     * @return string
     */
    public function getTitle() {
        $title = $this->view->headTitle();
        $this->_title = strip_tags( $title->toString() );

        return $this->_title;
    }

    /** Function to return
     * @access public
     * @return \Pas_View_Helper_Title
     */
    public function title() {
        return $this;
    }
}
