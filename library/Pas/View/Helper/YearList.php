<?php

/**
 * A view helper for generating a list of years and associated links
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->yearList()->setStartYear(1999);
 * ?>
 * </code>
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    GNU Public
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @example path description
 */
class Pas_View_Helper_YearList extends Zend_View_Helper_Abstract
{

    /** The current year
     * @access protected
     * @var int
     */
    protected $_currentYear;

    /** The start year for the array
     * @access protected
     * @var int
     */
    protected $_startYear = 1998;

    /** Get the start year
     * @access public
     * @return int
     */
    public function getStartYear()
    {
        return $this->_startYear;
    }

    /** Set the start year
     * @access public
     * @param int $startYear
     * @return \Pas_View_Helper_YearList
     */
    public function setStartYear($startYear)
    {
        $this->_startYear = $startYear;
        return $this;
    }

    /** Get the current year
     * @access public
     * @return int
     */
    public function getCurrentYear()
    {
        $this->_year = date('Y');
        return $this->_year;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_YearList
     */
    public function yearList()
    {
        return $this;
    }

    /** Generate the array of years
     * @access public
     * @return array
     */
    public function generateArray()
    {
        $years = range($this->getStartYear(), $this->getCurrentYear());
        $yearsList = array();
        foreach ($years as $key => $value) {
            $yearsList[] = array('year' => $value);
        }
        return $yearsList;
    }
}