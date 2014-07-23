<?php 
/** Controller for getting the guides that the Scheme produces
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Content
 * @version 1
*/
class Getinvolved_GuidesController extends Pas_Controller_Action_Admin {

    /** The init function
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->acl->allow(null);
    }
    
    /** Show the intro content for the section
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getContent('getinvolved',$this->getRequest()->getParam('slug'));	
    }
}