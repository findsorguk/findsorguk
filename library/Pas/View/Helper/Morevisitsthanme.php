<?php
/**
 * A trivial view helper to work out who has visited more times than you
 *
 * An example use:
 *
 * <code>
 * <?php
 * echo $this->moreVisitsThanMe()->setVisits(2);
 * ?>
 * </code>
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
        return $this->_visits;
    }

    /** Set the number of visits
     * @access public
     * @param int $visits
     * @return \Pas_View_Helper_MoreVisitsThanMe
     */
    public function setVisits($visits) {
        $this->_visits = $visits;
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        $users = new Users();
        $visits = $users->getMoreTotals($this->getVisits());
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
