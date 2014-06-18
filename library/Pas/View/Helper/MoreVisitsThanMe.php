
<!-- saved from url=(0141)https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/MoreVisitsThanMe.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body about="https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/MoreVisitsThanMe.php"><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/**
 * A trivial view helper to work out who has visited more times than you
 *
 * An example use:
 *
 * &lt;code&gt;
 * &lt;?php
 * echo $this-&gt;moreVisitsThanMe()-&gt;setVisits(2);
 * ?&gt;
 * &lt;/code&gt;
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_MoreVisitsThanMe extends Zend_View_Helper_Abstract
{

    /** The number of visits made
     * @access protected
     * @var int
     */
    protected $_visits;

    /** Get the number of visits
     * @access protected
     * @return int
     */
    public function getVisits() {
        return $this-&gt;_visits;
    }

    /** Set the number of visits
     * @access public
     * @param int $visits
     * @return \Pas_View_Helper_MoreVisitsThanMe
     */
    public function setVisits($visits) {
        $this-&gt;_visits = $visits;
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        $users = new Users();
        $visits = $users-&gt;getMoreTotals($this-&gt;getVisits());
        foreach ($visits as $v) {
            $total = $v['morethan'];
        }
        return $total;
    }

    /** Find out who has visited more times than a person
     * @access public
     * @return \Pas_View_Helper_MoreVisitsThanMe
     */
    public function moreVisitsThanMe() {
        return $this;
    }
}
</pre></body></html>