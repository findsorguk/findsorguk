<?php
/** View helper for displaying the number of changes for coin records from audit table
 *
 * An example of use:
 * <code>
 * <?php
 *
 * ?>
 * </code>
 * @category Pas
 * @package View
 * @subpackage Helper
 * @uses Pas_View_Helper_TimeAgoInWords
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright DEJ PETT
 * @author Daniel Pett
 * @version 1
 * @since September 29 2011
 */
class Pas_View_Helper_ChangesCoins extends Zend_View_Helper_Abstract
{

    /** The ID to query
     * @access protected
     * @var int
     */
    protected $_id;

    /** get the ID to query
     * @access public
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the ID to query
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_ChangesCoins
     */
    public function setId( int $id) {
        $this->_id = $id;
        return $this;
    }

    /** The nothing returned error
     *
     */
    const NOTHING = '<p>No changes made so far.</p>';

    /** Get the role for the user
     * @access protected
     * @return string
     */
    protected function _getRole(){
        $role = new Pas_User_Details();
        return $role->getPerson()->role;

    }

    /** Array of allowed roles
     * @access protected
     * @var array
     */
    protected $_allowed = array(
        'treasure', 'flos', 'fa',
        'admin', 'hero', 'hoard'
    );

    /** Build the html from data array
     * @param array $a
     * @return string $html
     */
    public function buildHtml($a) {
        $html = '';
        $html .= '<li><a class="overlay" href="';
        $html .= $this->view->url(array(
                'module' => 'database',
                'controller' => 'ajax',
                'action' => 'coinaudit',
                'id' => $a['editID']),
                null,
                true);
        $html .= '" title="View all changes on this date">';
        $html .= $this->view->timeAgoInWords($a['created']);
        $html .= '</a> ';
        $html .= $a['fullname'];
        $html .= ' edited this record.</li>';
        return $html;
    }

    /** The method for the helper
     * @access public
     * @return \Pas_View_Helper_ChangesCoins
     */
    public function changesCoins() {
        return $this;
    }

    /** Get the data from the model
     * @access public
     * @return string
     */
    public function getData() {
        $html = '';
        if(in_array($this->_getRole(), $this->_allowed)){
            $audit = new CoinsAudit();
            $auditdata = $audit->getChanges( $this->getId() );
            if($auditdata) {
                $html .= '<h5>Coin data audit</h5>';
                $html .= '<ul id="related">';
                $html .= $this->parseArray( $auditdata );
                $html .= '</ul>';

                } else {
                    $html .= self::NOTHING;
                }
        }
        return $html;
    }

    /** To string html
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getData();
    }

    /** Parse the array
     * @access public
     * @param array $array
     * @return typ
     */
    public function parseArray( array $array ) {
        $html = '';
        foreach($array as $a) {
                    $html .= $this->buildHtml($a);
                }
        return $html;
    }
}