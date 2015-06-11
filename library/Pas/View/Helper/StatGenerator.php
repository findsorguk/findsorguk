<?php
/** Class for generating stats for the database
 *
 * Example of use:
 * <code>
 * <?php
 * echo $this->statGenerator()->setStats($this->stats);
 * ?>
 * </code>
 *
 * @uses Exception Zend_Exception
 * @uses partial Zend_View_Helper_Partial
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 1
 * @category Pas
 * @package View
 * @subpackage Helper
 * @copyright (c) Daniel Pett, The British Museum
 * @example /app/modules/database/views/scripts/myscheme/myfinds.phtml
 */
class Pas_View_Helper_StatGenerator extends Zend_View_Helper_Abstract
{
    /** The array for the stats to be generated from
     * @access protected
     * @var array
     */
    protected $_stats = array();

    /** Get the stats array
     * @access protected
     * @return array
     */
    public function getStats()
    {
        return $this->_stats;
    }

    /** Set up the stats array
     * @access public
     * @param  array $stats
     * @return \Pas_View_Helper_StatGenerator
     * @throws Zend_Exception
     */
    public function setStats(array $stats)
    {
        if (is_array($stats)) {
            $this->_stats = $stats;
        } else {
            throw new Zend_Exception('You need to use an array', 500);
        }

        return $this;
    }

    /** Magic method to string
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->html();
    }

    /** Generate the html
     * @access public
     * @return string
     */
    public function html()
    {
        return $this->view->partial('partials/database/structural/statSearch.phtml',
            $this->getStats());
    }

    /** The stat generator function
     * @access public
     * @return \Pas_View_Helper_StatGenerator
     */
    public function statGenerator()
    {
        return $this;
    }
}