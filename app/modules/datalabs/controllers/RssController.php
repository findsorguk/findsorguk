<?php
/** Controller for RSS section
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Datalabs_RssController extends Pas_Controller_Action_Admin {
	
    /** Setup the contexts by action and the ACL.
    */
    public function init(){
    $this->_helper->acl->allow('public', null);
    }
    /** Display list of RSS feeds.
    */	
    public function indexAction(){
    $content = new Content();
    $this->view->contents = $content->getFrontContent('rss');
    }

}