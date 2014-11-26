<?php

/**
 * A view helper for setting up a search link
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->searchLink()->setId($id);
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @example /app/modules/database/views/scripts/terminology/preservation.phtml
 * @todo Probably get rid of this!
 */
class Pas_View_Helper_SearchLink extends Zend_View_Helper_Abstract
{

    /** Id number to query
     * @access protected
     * @var int
     */
    protected $_id;

    protected $_field;

    /** The page parameter
     * @access public
     * @var string
     */
    protected $_parameter;

    /** Get the parameter
     * @access public
     * @return string
     */
    public function getParameter()
    {
        $this->_parameter = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        return $this->_parameter;
    }

    public function setField($field)
    {
        $this->_field = $field;
        return $this;
    }

    public function getField()
    {
        if (is_null($this->_field)){
            return $this->getParameter();
    } else {
        return $this->_field;
    }
    }

    /** Get the id to query
     * @access public
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /** Set the id number
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_SearchLink
     */
    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    /** Function to return
     * @access public
     * @return \Pas_View_Helper_SearchLink
     */
    public function searchLink()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        $url = $this->view->url(array(
                'module' => 'database',
                'controller' => 'search',
                'action' => 'results',
                $this->getField() => urlencode($this->getId())
            ),
            null, true);

        $string = '<p>Search the database for <a href="';
        $string .= $url;
        $string .= '" title="Search the database for examples">all examples</a>';
        $string .= ' recorded.</p>';
        return $string;
    }
}
