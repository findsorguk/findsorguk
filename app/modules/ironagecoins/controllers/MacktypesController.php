<?php
/** Controller for Iron Age period's mack types
 * This listing is now pretty much obsolete, but is retained for concordance. 
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Pas_Exception_Param
 * @uses MackTypes
*/
class IronAgeCoins_MacktypesController extends Pas_Controller_Action_Admin {
    
    /** The Mack types model
     * @access public
     * @var \MackTypes
     */
    protected $_mackTypes;
	
    /** The init function
     * @access public
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow(null);
	$this->_helper->contextSwitch()->setAutoJsonSerialization(false);
	$this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addActionContext('index', array('xml','json'))
		->addActionContext('type', array('xml','json'))
		->initContext();
	$this->_mackTypes = new MackTypes();
    }
    
    /** Internal period ID number for the Iron Age
     * @access protected
     * @var integer
     */
    protected $_period = 16;
    
    /** Set up the Mack type index pages
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->macks = $this->_mackTypes->getMackTypes($this->getAllParams());
    }
    
    /** An individual mack type
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function typeAction(){
        if($this->_getParam('id',false)) {
            $this->view->type = $this->_mackTypes->fetchRow(
                    $this->_mackTypes->select()
                    ->where('type = ?',urlencode($this->_getParam('id'))
                            ));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}
