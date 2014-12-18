<?php

/**
 * A view helper for getting a count of SMR records within a constituency
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @version 1
 * @uses viewHelper Pas_View_Helper
 * @uses ScheduledMonuments
 */
class Pas_View_Helper_SmrDataToConst extends Zend_View_Helper_Abstract
{

    /** The constituency to query
     * @access protected
     * @var type
     */
    protected $_constituency;

    /** Get the constituency to query
     * @access public
     * @return string
     */
    public function getConstituency()
    {
        return $this->_constituency;
    }

    /** Set the constituency
     * @access public
     * @param  string $constituency
     * @return \Pas_View_Helper_SmrDataToConst
     */
    public function setConstituency($constituency)
    {
        $this->_constituency = $constituency;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_SmrDataToConst
     */
    public function smrDataToConst()
    {
        return $this;
    }

    /** Get the data
     * @access public
     * @return function
     */
    public function getData()
    {
        $os = $this->getRecords($this->getConstituency());

        return $this->buildHtml($os);
    }

    /** Get the records from the database
     * @access public
     * @param  string $constituency
     * @return array
     */
    public function getRecords($constituency)
    {
        $osdata = new ScheduledMonuments();
        return $osdata->getSmrsConstituency($constituency);
    }

    /** Build the HTML
     * @access public
     * @param  array $os
     * @return string
     */
    public function buildHtml(array $os)
    {
        $html = '';
        if (is_array($os)) {
            $data = array('count' => count($os));
            $html .= $this->partial('partials/database/geodata/smrConstituencyCount.phtml', $data);
        }

        return $html;
    }
}
