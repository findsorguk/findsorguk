<?php
/**
 * A view helper for getting a count of OS antiquity/roman records within a
 * constituency
 *
 * @category Pas
 * @package Pas_View
 * @subpackage Helper
 * @license GNU
 * @copyright Daniel Pett
 * @author Daniel Pett
 * @version 1
 * @uses viewHelper Pas_View_Helper extends Zend_View_Helper_Abstract
 */
class Pas_View_Helper_OsDataToConst extends Zend_View_Helper_Abstract
{

    /** The constituency to query
     * @access protected
     * @var string
     */
    protected $_constituency;

    /** Get the constituency
     * @access public
     * @return string
     */
    public function getConstituency()
    {
        return $this->_constituency;
    }

    /** Set the constituency to query
     * @access public
     * @param  string                         $constituency
     * @return \Pas_View_Helper_OsDataToConst
     */
    public function setConstituency( $constituency)
    {
        $this->_constituency = $constituency;

        return $this;
    }

    /** the function to return
     * @access public
     * @return \Pas_View_Helper_OsDataToConst
     */
    public function osDataToConst()
    {
        return $this;
    }

    /** Get the data from the model
     * @access public
     * @return string
     */
    public function getData()
    {
        $os = $this->getRecords($this->getConstituency());

        return $this->buildHtml($os);
    }

    /** The to string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getData();
    }

    /** Get the records from the model
     * @access protected
     * @param  string $constituency
     * @return array
     */
    public function getRecords( $constituency)
    {
        $osdata = new Osdata();

        return $osdata->getGazetteerConstituency($constituency);
    }

    /** Build html
     * @access public
     * @param  array  $os
     * @return string
     */
    public function buildHtml(array $os)
    {
        $html = '';
        if (is_array($os)) {
            $html .= '<p>There are ';
            $html .= count($os);
            $html .= ' mapped ancient sites (Roman and other) listed in the OS';
            $html .= '1:50K Gazetteer for this constituency.</p>';
        }

        return $html;
    }

}
