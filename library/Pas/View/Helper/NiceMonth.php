<?php
/** A view helper to translate integers to month
 *
 * Example use:
 *
 * <code>
 * <?php
 * echo $this->niceMonth()->setDate($date);
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 *
 *
 */
class Pas_View_Helper_NiceMonth extends Zend_View_Helper_Abstract
{
    /** Integer to query
     * @access protected
     * @var int
     */
    protected $_date;

    /** Get the date to query
     * @access public
     * @return int
     */
    public function getDate() {
        return $this->_date;
    }

    /** Set the date to query
     * @access public
     * @param int $date
     * @return \Pas_View_Helper_NiceMonth
     */
    public function setDate($date) {
        $this->_date = $date;
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_NiceMonth
     */
    public function niceMonth() {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
       switch ($this->getDate()) {
        case 01:
            $month = 'January';
            break;
        case 02:
            $month = 'February';
            break;
        case 03:
            $month = 'March';
            break;
        case 04:
            $month = 'April';
            break;
        case 05:
            $month = 'May';
            break;
        case 06:
            $month = 'June';
            break;
        case 07:
            $month = 'July';
            break;
        case 8:
            $month = 'August';
            break;
        case 9:
            $month = 'September';
            break;
        case 10:
            $month = 'October';
            break;
        case 11:
            $month = 'November';
            break;
        case 12:
            $month = 'December';
            break;
        default:
            return $month = $this->getDate();
            break;
       }

    return $month;
    }
 }