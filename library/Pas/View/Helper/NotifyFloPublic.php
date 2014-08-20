<?php
/** A view helper for rendering links for checking records
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * echo $this->notifyFloPublic()
 * ->setInstitution($this->finds['0']['institution'])
 * ->setId($this->finds['0']['id'])
 * ->setWorkflow($this->finds['0']['secwfstage']);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @since 20/1/2012
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @copyright (c) 2014 Daniel Pett
 * @uses Pas_User_Details
 * @uses Pas_Exception
 * @example /app/modules/database/views/scripts/artefacts/record.phtml
 */
class Pas_View_Helper_NotifyFloPublic extends Zend_View_Helper_Abstract {
    
    /** The allowed array of roles
     * @access protected
     * @var array
     */
    protected $_allowed = array( 'member', 'flos', 'treasure', 'admin', 'fa' );

    /** The user object
     * @access protected
     * @var \Pas_User_Details
     */
    protected $_user;
    
    /** The institution of the record
     * @access protected
     * @var string
     */
    protected $_institution;
    
    /** The ID of the record
     * @access protected
     * @var integer
     */
    protected $_id;
    
    /** The workflow of the object
     * @access protected
     * @var integer
     */
    protected $_workflow;
    
    /** Get the institution
     * @access public
     * @return string
     */
    public function getInstitution() {
        return $this->_institution;
    }

    /** Get the ID of the object
     * @access public
     * @return integer
     */
    public function getId() {
        return $this->_id;
    }

    /** Get the workflow of the records
     * @access public
     * @return integer
     */
    public function getWorkflow() {
        return $this->_workflow;
    }

    /** Set the institution
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_NotifyFloPublic
     */
    public function setInstitution($institution) {
        $this->_institution = $institution;
        return $this;
    }

    /** Set the ID for the record
     * @access public
     * @param integer $id
     * @return \Pas_View_Helper_NotifyFloPublic
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** Set the workflow of the record
     * @access public
     * @param integer $workflow
     * @return \Pas_View_Helper_NotifyFloPublic
     */
    public function setWorkflow($workflow) {
        $this->_workflow = $workflow;
        return $this;
    }

    public function getUser() {
       $user = new Pas_User_Details();
        if ($user) {
            $this->_user = $user->getPerson();
        } else {
            throw new Pas_Exception('No user credentials found', 500);
        }
        return $this->_user;
    }
    
    /** To string function
     * @access public
     * @return string The Html for the view
     */
    public function __toString(){
        $html = '';
        if(($this->getWorkflow() < 3) && ($this->getInstitution() === 'PUBLIC')
            && in_array($this->getUser()>role, $this->_allowed)){
            $html .= $this->_buildHtml($this->getId());
        } 
        return $html;
    }

    /** Render the html
     * @access public
     * @param int $id
     * @return string 
     */
    private function _buildHtml($id) {
        $html = '<div>';
        $html .= '<p><a class="btn btn-large btn-info" href ="';
        $html .= $this->view->serverUrl() . '/database/artefacts/notifyflo/id/' . $id;
        $html .= '" title="Get this published">Get this record checked or published by your flo</a></p></div>';
        return $html;
    }
    
    /** The function
     * @access public
     * @return \Pas_View_Helper_NotifyFloPublic
     */
    public function notifyFloPublic() {
        return $this;
    }
}