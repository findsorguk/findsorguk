<?php
/** Controller for displaying information topics
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/
class Info_AdviceController extends Pas_Controller_Action_Admin {
	
    /** Setup the contexts by action and the ACL.
    */
    public function init(){
    $this->_helper->acl->allow('public', null);
    }
    /** Display the list of topics or individual pages.
    */	
    public function indexAction(){
    $content = new Content();
    $this->view->contents = $content->getContent('info', 
            $this->_getParam('slug'));
    }

}