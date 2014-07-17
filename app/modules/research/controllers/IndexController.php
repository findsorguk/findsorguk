<?php
/** Controller for introducing the research topics
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
*/
class Research_IndexController extends Pas_Controller_Action_Admin
{
	/** Initialise the ACL and contexts
	*/ 
	public function init() {
 	$this->_helper->_acl->allow(null);
    } 
	/** Initialise the index pages
	*/ 
	public function indexAction() {
 	
		$content = new Content();
		$this->view->contents = $content->getFrontContent('research');
	
		$research = new ResearchProjects();
		$this->view->research = $research->getCounts();
    
	}
}