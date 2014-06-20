<?php
/** 
 * A view helper for displaying online accounts
 * 
 * Example of use:
 * <code>
 * <?php
 * echo $this->onlineAccountHtmlPublic()->setId($id);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @example /app/views/scripts/partials/users/publicprofile.phtml
 */
class Pas_View_Helper_OnlineAccountHtmlPublic extends Zend_View_Helper_Abstract {

     /** The id number to query
     * @access public
     * @var string
     */
    protected $_id;
    
    /** Get the ID number
     * @access public
     * @return int
     */
    public function getId() {
        return $this->_id;
    }

    /** Set the ID number
     * @access public
     * @param string $id
     * @return \Pas_View_Helper_OnlineAccountHtml
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }
    
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_OnlineAccountHtmlPublic
     */
    public function onlineAccountHtmlPublic() {
        return $this;
    }
    
    /** Get account data
     * @access public
     * @param int $id
     * @return string
     */
    public function getAccounts($id) {
        $accts = new OnlineAccounts();
        return $accts->getAllAccounts($id);
    }

    /** Build the html
     * @access public
     * @param array $data
     */
    public function buildHtml( $data ) {
        $html ='';
        $html .= '<p>Social profiles: ';
        $html .= '<div class="btn-group">';
        $html .=  $this->view->partialLoop('partials/contacts/foafAccts.phtml',
                $data);
        $html .= '</div>';
        $html .= '</p>';
        return  $html;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml($this->getAccounts($this->getId()));
    }
}
