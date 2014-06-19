
<!-- saved from url=(0134)https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/NiceMonth.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body about="https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/NiceMonth.php"><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/** A view helper to translate integers to month
 *
 * Example use:
 *
 * &lt;code&gt;
 * &lt;?php
 * echo $this-&gt;niceMonth()-&gt;setDate($date);
 * ?&gt;
 * &lt;/code&gt;
 * @author Daniel Pett &lt;dpett@britishmuseum.org&gt;
 * @version 1
 * @category Pas
 * @package Pas_View_Helper
 * @license http://URL name
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
        return $this-&gt;_date;
    }

    /** Set the date to query
     * @access public
     * @param int $date
     * @return \Pas_View_Helper_NiceMonth
     */
    public function setDate($date) {
        $this-&gt;_date = $date;
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
       switch ($this-&gt;getDate()) {
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
            return $month = $this-&gt;getDate();
            break;
       }

    return $month;
    }

    }
</pre></body></html>