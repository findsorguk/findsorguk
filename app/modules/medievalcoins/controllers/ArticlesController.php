<?php
/** Controller for displaying articles from the Medieval coin guide
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 * @uses Pas_Exception_Param
 */
class Medievalcoins_ArticlesController extends Pas_Controller_Action_Admin {
    
    /** The content model
     * @access protected
     * @var \Content
     */
    protected $_content;
            
    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init(){
        
        $this->_helper->acl->allow('public',null);
        $this->_content = new Content();
    }
    /** Setup the front article pages
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->contents = $this->_content->getSectionContents('medievalcoins');
    }
    /** Setup an individual page
     * @access public
     * @return void
     */
    public function pageAction() {
        if($this->getParam('slug',0)) {
        $this->view->contents = $content->getContent('medievalcoins', (string)$this->getParam('slug'));
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}
