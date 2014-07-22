<?php
/** Controller for displaying advice pages for the conservation notes module.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Content
 * @uses Pas_Exception_Param
 */
class Conservation_AdviceController extends Pas_Controller_Action_Admin {
    
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->acl->allow('public',null);
    }
	
    /** Set up each page
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function indexAction() {
        if($this->_getParam('slug',false)){
            $content = new Content();
            $this->view->contents = $content->getContent('conservation',$this->_getParam('slug'));
        } else {
            throw new Pas_Exception_Param('That page is not found.', 404);
        }
    }

}
