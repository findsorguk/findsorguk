<?php
/** A view helper for rendering links if permission allows for saving searches.
 * 
 * An example of use:
 * <code>
 * <?php
 * echo $this->emailSaveSearch();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 * @uses Pas_User_Details 
 */
class Pas_View_Helper_EmailSaveSearch extends Zend_View_Helper_Abstract
{
    
    /** The default role
     * @access protected
     * @var string
     */
    protected $_role = 'public';

    /** The roles allowed access to the resources
     * @access public
     * @var array
     */
    protected $_allowed = array('member', 'flos', 'admin', 'treasure', 'hero', 'fa' );
    
    /** The simple link parameters for sending to the url helper
     * 
     * @access protected
     * @var array Key value pairs for url params
     */
    protected $_simple = array(
        'module' => 'database',
        'controller' => 'search');
    
    /** The advanced link parameters for sending to the url helper
     * 
     * @access protected
     * @var array $_advanced; Key value pairs for url params
     */
    protected $_advanced = array(
            'module' => 'database',
            'controller' => 'search',
            'action' => 'advanced');
    
    /** The save link parameters for sending to the url helper
     * 
     * @access protected
     * @var array Key value pairs for url params
     */
    protected $_save = array(
            'module' => 'database',
            'controller' => 'search',
            'action' => 'save');
    
    /** The email link parameters for sending to the url helper
     * 
     * @access protected
     * @var array Key value pairs for url params
     */
    protected $_email = array(
            'module' => 'database',
            'controller' => 'search',
            'action' => 'email');

    /** Get the user role
     * @access public
     * @return string $_role;
     */
    public function getRole() {
        $person = new Pas_User_Details();
        $details = $person->getPerson();
        if($details){
            $this->_role = $details->role;
        }
        return $this->_role;
    }

    /** Build the html
     * @access public
     * @return string
     */
    protected function buildHtml() {
        $simple = '<a href="';
        $simple .= $this->view->url($this->_simple,'default',true);
        $simple .= '">Back to simple search</a>';
        
        $advanced = '<a href="';
        $advanced .= $this->view->url($this->_advanced, 'default',true);
        $advanced .= '">Back to advanced search</a>';
        
        $email = '<a href="';
        $email .= $this->view->url($this->_email, 'default',true);
        $email .= '">Send this search to someone</a>';
        
        $save = '<a href="';
        $save .= $this->view->url($this->_save,'default',true);
        $save .= '">Save this search</a>';

        if (in_array($this->getRole(), $this->_allowed)) {
            $urls = array($simple, $advanced, $email, $save);
        } else {
            $urls = array($simple, $advanced);
        }
        
        $html = '<p>';
        $html .= implode(' | ', $urls);
        $html .= '</p>';

        return $html;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_EmailSaveSearch
     */
    public function emailSaveSearch() {
        return $this;
    }
    /** The function to return string of html
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml();
    }

}
