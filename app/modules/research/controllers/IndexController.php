<?php
/** Controller for introducing the research topics
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @copyright (c) 2014 Daniel Pett
 * @uses Content
 * @uses ResearchProjects
 * 
*/
class Research_IndexController extends Pas_Controller_Action_Admin
{
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
 	$this->_helper->_acl->allow(null);
    } 
	
    /** Initialise the index pages
     * @access public
     * @return void
     */
    public function indexAction() {
        $content = new Content();
        $this->view->contents = $content->getFrontContent('research');
        $research = new ResearchProjects();
        $this->view->research = $research->getCounts();
    }
}