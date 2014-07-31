<?php 
/** Controller for all rendering the advice section of the treasure module
 * 
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Content
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * 
*/
class Treasure_AdviceController extends Pas_Controller_Action_Admin {

    /** Init the controller
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->acl->allow(null);
        
    }
	
    /** The index action
     * @access public
     * @return void
     */
    public function indexAction()	{
        $content = new Content();
        $this->view->contents = $content->getContent('treasure',$this->getRequest()->getParam('slug'));	
    }
        
    /** Treasure minutes
     * @access public
     * @return void
     */
    public function minutesAction(){
        //Magic in view
    }
}