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
    protected $_id = null;
    
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
    
    public function onlineAccountHtmlPublic() {
        Zend_Debug::dump($this);
        Zend_Debug::dump($this->getId());
        exit;
        return $this;
    }
    
    public function getAccounts($id) {
        $accts = new OnlineAccounts();
        $data = $accts->getAllAccounts($id);
        $this->buildHtml($data);
    }

    /** Build the html
     * @access public
     * @param array $data
     */
    public function buildHtml( $data) {
        $html ='';
        $html .= '<p>Social profiles: ';
        $html .=  $this->view->partialLoop('partials/contacts/foafAccts.phtml',$data);
        $html .= '</p>';
        echo $html;
    }

    public function __toString() {
        return $this->getAccounts($this->getId());
    }
}
