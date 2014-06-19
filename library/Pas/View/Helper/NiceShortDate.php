
<!-- saved from url=(0138)https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/NiceShortDate.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body about="https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/NiceShortDate.php"><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/**
 * A view helper for displaying a short human readable date
 *
 * Example use:
 * &lt;code&gt;
 * &lt;?php
 * echo $this-&gt;niceShortDate()-&gt;setDate($date);
 * ?&gt;
 * &lt;/code&gt;
 * 
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett &lt;dpett@britishmuseum.org&gt;
 * @version 1
 */

class Pas_View_Helper_NiceShortDate extends Zend_View_Helper_Abstract
{
    /** The date string
     * @access protected
     * @var string
     */
    protected $_date;

    /** Get the date to query
     * @access public
     * @return string
     */
    public function getDate() {
        return $this-&gt;_date;
    }

    /** Set the date
     * @access public
     * @param string|int $date
     * @return \Pas_View_Helper_NiceShortDate
     */
    public function setDate($date) {
        $this-&gt;_date = $date;
        return $this;
    }

    /**
     * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
     *
     * @param  string $date_string Datetime string
     * @return string Formatted date string
     * @access public
     */
    private function fromString($date_string) {
        if (is_integer($date_string) || is_numeric($date_string)) {
            return intval($date_string);
        } else {
            return strtotime($date_string);
        }
    }
    /** Format the date as wanted for this view helper
     * @access public
     * @param  string $date_string
     * @return string $ret
     */
    public function __toString() {
        $date = $this-&gt;fromString($this-&gt;getDate());
        $ret = date('l jS F Y', $date);
        return $ret;
    }
    /** The function
     * @author Daniel Pett &lt;dpett@britishmuseum.org&gt;
     * @access public
     * @return \Pas_View_Helper_NiceShortDate
     */
    public function niceShortDate() {
        return $this;
    }
}</pre></body></html>