<?php
/**
 * A view helper for displaying page title without the <title></title> tags
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Zend_View_Helper_HeadTitle
 */
class Pas_View_Helper_Title extends Zend_View_Helper_Abstract {

    protected $_title = 'The Portable Antiquities Scheme';

    /** Magic method
     *
     * @return string
     */
    public function __toString() {
        return $this->getTitle();
    }

    /** Get the title
     *
     * @return string
     *
     */
    public function getTitle() {
        $title = $this->view->headTitle();
        $this->_title = strip_tags( $title->toString() );
        return $this->_title;
    }

    /** Function
     *
     * @return \Pas_View_Helper_Title
     */
    public function title() {
        return $this;
    }
}