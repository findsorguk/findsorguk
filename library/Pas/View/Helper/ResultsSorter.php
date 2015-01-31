<?php

/**
 * A view helper for setting up a results sorter
 *
 * This helper is used on any page where results are taken from the solr index
 * and then offers the user the chance to sort. It could be set up, so that
 * the user could have a default sort column and order in their preferences
 * (one day!)
 * Example of use:
 * <code>
 * <?php
 * echo $this->resultsSorter()->setResults($this->paginator);
 * ?>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @package Pas
 * @category Pas_View_Helper
 * @version 1
 */
class Pas_View_Helper_ResultsSorter extends Zend_View_Helper_Abstract
{

    /** The default fields for sort
     * This is set mainly for finds listers
     * @access protected
     * @var array
     */
    protected $_fields = array(
        'Date created' => 'created',
        'Object type' => 'objectType',
        'Broad period' => 'broadperiod',
        'Recording institution' => 'institution',
        'Workflow status' => 'workflow',
        'Updated' => 'updated'
    );

    /** The default sort direction
     * @access protected
     * @var string
     */
    protected $_defaultDirection = 'desc';

    /** The default sort column
     * @access protected
     * @var string
     */
    protected $_defaultSort = 'created';

    /** The request for pulling out parameters
     * @access protected
     * @var object
     */
    protected $_request;

    /** The default direction pairs
     * @access protected
     * @var array
     */
    protected $_direction = array('descending' => 'desc', 'ascending' => 'asc');

    /** The solr results
     * @access public
     * @var object
     */
    protected $_results;

    /** Get the results
     * @access public
     * @return object
     */
    public function getResults()
    {
        return $this->_results;
    }

    /** Set the results
     * @access public
     * @param Zend_Paginator $results
     * @return \Pas_View_Helper_ResultsSorter
     */
    public function setResults(Zend_Paginator $results)
    {
        $this->_results = $results;
        return $this;
    }

    /** Get the fields for sorting
     * @access public
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /** Get the default sort direction
     * @access public
     * @return string
     */
    public function getDefaultDirection()
    {
        return $this->_defaultDirection;
    }

    /** Get the default sort
     * @access public
     * @return string
     */
    public function getDefaultSort()
    {
        return $this->_defaultSort;
    }

    /** Get the direction
     * @access public
     * @return string
     */
    public function getDirection()
    {
        return $this->_direction;
    }

    /** Set the fields to sort on
     * @access public
     * @param array $fields
     * @return \Pas_View_Helper_ResultsSorter
     */
    public function setFields(array $fields)
    {
        $this->_fields = $fields;
        return $this;
    }

    /** Set the default sort direction
     * Unused at present
     * @access public
     * @param string $defaultDirection
     * @return \Pas_View_Helper_ResultsSorter
     */
    public function setDefaultDirection($defaultDirection)
    {
        $this->_defaultDirection = $defaultDirection;
        return $this;
    }

    /** Set the default sort direction
     * Unused at present
     * @access public
     * @param string $defaultSort
     * @return \Pas_View_Helper_ResultsSorter
     */
    public function setDefaultSort($defaultSort)
    {
        $this->_defaultSort = $defaultSort;
        return $this;
    }

    /** Set the direction
     * @access public
     * @param string $direction
     * @return \Pas_View_Helper_ResultsSorter
     */
    public function setDirection($direction)
    {
        $this->_direction = $direction;
        return $this;
    }

    /** Get the request object
     * @access public
     * @return object
     */
    public function getRequest()
    {
        $this->_request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
        return $this->_request;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ResultsSorter
     */
    public function resultsSorter()
    {
        return $this;
    }

    /** The to string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        $html = '';
        if ($this->getResults()) {
            $html = $this->_buildHtmlField();
            $html .= $this->_buildHtmlDirection();
        }
        return $html;

    }

    /** Build the fields to sort
     * @access public
     * @return string
     */
    protected function _buildHtmlField()
    {
        $request = $this->getRequest();
        $html = '<p>Sort your search by: </p>';
        $html .= '<ul>';
        if(array_key_exists('sort', $request)) {
            $key = $request['sort'];
        } else {
            $key = 'created';
        }

        foreach ($this->getFields() as $k => $v) {
            $request['sort'] = $v;
            $html .= '<li><a href="' . $this->view->url($request, 'default', true) . '"';
            if (array_key_exists('sort', $request) && $request['sort'] === $key) {
                $html .= ' class="label label-important" ';
            } elseif (!array_key_exists('sort', $request) && $v === 'created') {
                $html .= ' class="label" ';
            }
            $html .= '>' . $k . '</a></li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /** Build the sort direction buttons
     * @access public
     * @return string
     */
    protected function _buildHtmlDirection()
    {
        $request = $this->getRequest();
        if(array_key_exists('direction', $request)) {
            $key = $request['direction'];
        } else {
            $key = 'desc';
        }
        $class = 'btn btn-mini ';
        $html = '<p>Which direction? </p>';
        $sorter = array();
        foreach ($this->getDirection() as $k => $v) {
            $request['direction'] = $v;
            switch ($v) {
                case 'desc':
                    $icon = 'down';
                    break;
                case 'asc':
                    $icon = 'up';
                    break;
                default:
                    $active = '';
                    break;
            }

            $sort = '<a href="' . $this->view->url($request, 'default', true) . '" ';
            if (array_key_exists('direction', $request) && $request['direction'] === $key) {
                $sort .= ' class="btn btn-mini btn-success" ';
            } elseif (array_key_exists('direction', $request) && $v === 'asc') {
                $sort .= ' class="btn btn-mini" ';
            } else {
                $sort .= ' class="btn-mini btn"';
            }
            $sort .= '>' . $k . ' <i class="icon-arrow-' . $icon . '"></i></a> ';
            $sorter[] = $sort;
        }
        $html .= '<div class="btn-group">';
        $html .= implode('', $sorter);
        $html .= '</div>';
        return $html;
    }

}
