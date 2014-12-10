<?php
/**
 * A view helper for displaying toolbox of links
 *
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_RecordEditDeleteLinks
 * @uses Pas_User_Details
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */

class Pas_View_Helper_Toolbox extends Zend_View_Helper_Abstract {
    
    /** The allowed roles for access
     * @access protected
     * @var array
     */
    protected $_allowed = array(
        'fa', 'flos', 'admin',
        'treasure', 'hoard'
    );

    /** The user's role
     * @access protected
     * @var string
     */
    protected $_role;
    
    /** Get the user's role
     * @access public
     * @return string
     */
    public function getRole() {
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if ($person) {
            $this->_role = $person->role;
        } else {
            $this->_role = 'public';
        }
        return $this->_role;
    }
    
    /** The record ID
     * @access protected
     * @var integer
     */
    protected $_id;
    
    /** The old find ID string
     * @access protected
     * @var string 
     */
    protected $_oldFindID;
    
    /** Created by 
     * @access protected
     * @var integer 
     */
    protected $_createdBy;

    /** The controller from which the call originates
     * @access protected
     * @var integer
     */
    protected $_controller;

    /** The record type that should be created, edited, deleted etc.
     * @access protected
     * @var integer
     */
    protected $_recordType;
    
    /** Get an id
     * @access public
     * @return integer
     */
    public function getId() {
        return $this->_id;
    }

    /** Get the old find ID
     * @access public
     * @return string
     */
    public function getOldFindID() {
        return $this->_oldFindID;
    }

    /** Get created by string
     * @access public
     * @return integer
     */
    public function getCreatedBy() {
        return $this->_createdBy;
    }
    
    /** Set the id number
     * @access public
     * @param integer $id
     * @return \Pas_View_Helper_Toolbox
     */
    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    /** Set the old findID
     * @access public
     * @param string $oldFindID
     * @return \Pas_View_Helper_Toolbox
     */
    public function setOldFindID($oldFindID) {
        $this->_oldFindID = $oldFindID;
        return $this;
    }

    /** Set the created by number
     * @access public
     * @param integer $createdBy
     * @return \Pas_View_Helper_Toolbox
     */
    public function setCreatedBy($createdBy) {
        $this->_createdBy = $createdBy;
        return $this;
    }

    /** Get the controller
     * @access public
     * @return object
     */
    public function getController() {
        $this->_controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        return $this->_controller;
    }

    /** Get the record type
     * @access public
     * @param string  $controller
     * @return object
     */
    public function getRecordType($controller) {
        if($controller == 'artefacts'){
            $this->_recordType = 'artefact';
        } elseif($controller == 'hoards'){
            $this->_recordType = 'hoard';
        }
        return $this->_recordType;
    }
    
    /** Display the toolbox, crappy code
     *
     * @param integer $id
     * @param string  $oldfindID
     * @param string  $createdBy
     */
    public function toolbox(){
        return $this;
    }
            
    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->buildHtml();
    }
    
    /** Return html string
     * @access public
     * @param string $controller
     * @return string
     */
    public function buildHtml() {
        $this->view->inlineScript()->appendFile(
                $this->view->baseUrl() . '/js/bootstrap-modal.js', 
                $type='text/javascript');
        $this->view->inlineScript()->appendFile(
                $this->view->baseUrl() . '/js/functionsRecord.js', 
                $type='text/javascript');

        $class = 'btn btn-small btn-primary';
        $html = '<div id="toolBox" class="btn-group">';

        $html .= $this->view->recordEditDeleteLinks()->setCreatedBy($this->getCreatedBy())->setFindID($this->getId())->setRecordID($this->getOldFindID())->setController($this->getController());

        $html .= $this->view->Href(array(
            'module' => 'database',
            'controller'=>$this->getController(),
            'action'=>'add',
            'checkAcl'=>true,
            'acl'=>'Zend_Acl',
            'content'=>'Add ' . $this->getRecordType($this->getController()) . ' <i class="icon-white icon-plus"></i>',
            'attribs' => array(
                'title' => 'Add new object',
                'accesskey' => 'a',
                'class' => 'btn btn-small btn-primary')
        ));


        if (in_array($this->getRole(),$this->_allowed)) {
            $html .= ' <a class="btn btn-small btn-danger" href="';
            $html .= $this->view->url(array(
                'module' => 'database',
                'controller'=>$this->getController(),
                'action'=>'workflow',
                'id' => $this->getId()),null,true);
            $html .= '">Change workflow</a>';
            $html .= ' <a class="' . $class . '"  href="';
            $html .= $this->view->url(array(
                'module' => 'database',
                'controller'=>'ajax',
                'action'=>'forceindexupdate',
                'id' => $this->getId()),null,true);
            $html .= '">Force index update</a>';
            $html .= '<a class="' . $class . '"  href="';
            $html .= $this->view->serverUrl() . $this->view->url(array(
                    'module' => 'database',
                    'controller' => 'ajax',
                    'action' => 'webcite',
                    'id' => $this->getId(),
                    'type' => $this->getRecordType($this->getController())
                ),null,true);
            $html .= '" title="Get citation information">Cite record</a> <a class="';
            $html .= $class . '" href="';
            $html .= $this->view->url(array(
                'module' => 'database',
                'controller' => 'ajax',
                'action' => 'embed',
                'id' =>  $this->getId(),
                'type' => $this->getRecordType($this->getController())
            ),null,true);
            $html .= '" title="Get code to embed this record in your webpage">Embed record</a> ';
            $html .=' <a class="' . $class . '" href="#print" id="print">Print';
            $html .= '<i class="icon-print icon-white"></i></a> ';
        }
        $html .= '</div>';
        return $html;
    }
}