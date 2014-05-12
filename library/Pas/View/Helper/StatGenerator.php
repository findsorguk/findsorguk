<?php
/** Class for generating stats for the database
 * @uses Exception Zend_Exception
 * @uses partial Zend_View_Helper_Partial
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license GNU
 * @version 1
 * @since 1
 * @category Pas
 * @package Pas_View_Helper
 * @copyright (c) Daniel Pett, The British Museum
 *
 */
class Pas_View_Helper_StatGenerator extends Zend_View_Helper_Abstract
{
    /** The array for the stats to be generated from
     *
     * @var array
     */
    protected $_stats = array();

    /** Get the stats array
     *
     * @return array
     */
    public function getStats() {
        return $this->_stats;
    }

    /** Set up the stats array
     * @access public
     * @param array $stats
     * @return \Pas_View_Helper_StatGenerator
     * @throws Zend_Exception
     */
    public function setStats(array $stats) {
        if(is_array ( $stats) ) {
            $this->_stats = $stats;
        } else {
            throw new Zend_Exception( 'You need to use an array', 500);
        }
        return $this;
    }

    /** Magic method to string
     *
     * @return string
     */
    public function __toString() {
        return $this->html();
    }

    /** Generate the html
     *
     * @return string
     */
    public function html() {
        return $this->view->partial('partials/database/statSearch.phtml',
                $this->getStats());
    }

    /** The stat generator function
     *
     * @return \Pas_View_Helper_StatGenerator
     */
    public function statGenerator() {
        return $this;
    }

}