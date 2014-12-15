<?php
/**
 * A view helper for displaying a user's record counts
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->userRecords()->setUsername($username);
 * ?>
 * </code>
 * @example /app/views/scripts/partials/users/publicProfile.phtml
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_UserRecords extends Zend_View_Helper_Abstract {
   
    /** The username
     * @access protected
     * @var string
     */
    protected $_username;
    
    /** Get the username
     * @access public
     * @return string
     */
    public function getUsername() {
        return $this->_username;
    }

    /** Set the username
     * @access public
     * @param string $username
     * @return \Pas_View_Helper_UserRecords
     */
    public function setUsername($username) {
        $this->_username = $username;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_UserRecords
     */
    public function userRecords(){
        return $this;
    }    
    
    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml($this->getData($this->getUsername()));
    }
    
    /** Build the html
     *  @access public
     * @param array $totals
     * @return string
     */
    public function buildHtml( array $totals ) {
        $html = '';
        if ($totals['0']['finds'] > 0) {
            $html .=  '<div class="object">';
            $html .= $totals['0']['finds'];
            $html .= ' finds within ';
            $html .= $totals['0']['records'];
            $html .= ' records.</div>';
        }
    return $html;
    }
    
    /** Get the totals for a user
     * @access public
     * @param string $username
     * @return array
     */
    public function getData( $username ) {
        $users = new Users();
        $ids = $users->getUserID($username);
        return $users->getCountFinds($ids['0']['id']);
    }
}