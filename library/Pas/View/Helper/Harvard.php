<?php

/**
 * A view helper for displaying references in correct Harvard bibliographic style
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Harvard extends Zend_View_Helper_Abstract
{

    /** The references
     * @access protected
     * @var array
     */
    protected $_refs = array();

    /** Get the references
     * @return mixed
     * @access public
     */
    public function getRefs()
    {
        return $this->_refs;
    }

    /** Set the references array
     * @param mixed $refs
     * @access public
     */
    public function setRefs($refs)
    {
        $this->_refs = $refs;
        return $this;
    }

    /** The class for returning the harvard book style
     * @access public
     * @return string
     */
    public function harvard()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        $html = '';
        foreach($this->getRefs() as $refs){
            $html .= $this->renderHtml($refs);
        }
        return $html;
    }

    /** Render new html
     * @access public
     * @return string
     *
     */
    public function renderHtml(array $refs)
    {
        $html = '';
        if (array_key_exists('publication_type', $refs)) {
            switch ($refs['publication_type']) {
                case  1:
                    $html .= $this->view->partial('partials/publications/harvard/style1.phtml', $refs);
                    break;
                case 2:
                    $html .= $this->view->partial('partials/publications/harvard/style2.phtml', $refs);
                    break;
                case 3:
                    $html .= $this->view->partial('partials/publications/harvard/style3.phtml', $refs);
                    break;
                case 4:
                    $html .= $this->view->partial('partials/publications/harvard/style4.phtml', $refs);
                    break;
                case 5:
                    $html .= $this->view->partial('partials/publications/harvard/style5.phtml', $refs);
                    break;
                case 6:
                    $html .= $this->view->partial('partials/publications/harvard/style6.phtml', $refs);
                    break;
                default:
                    $html .= $this->view->partial('partials/publications/harvard/default.phtml', $refs);
                    break;
            }
        }
        return $html;
    }
}