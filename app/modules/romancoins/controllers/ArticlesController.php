<?php
/** Controller for displaying Roman articles within the coin guide
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
 */
class Romancoins_ArticlesController extends Pas_Controller_Action_Admin {
    
    /** The content model
     * @access protected
     * @var \Content
     */
    protected $_content;
    
    /** Set up the ACL and contexts
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->acl->allow('public',null);
        $this->_content = new Content();
        
    }
    
    /** Set up the index page
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->view->contents = $this->_content->getSectionContents('romancoins');
    }
    /** Set up individual page
     * @access public
     * @return void
    */	
    public function pageAction() {
        $this->view->contents = $this->_content->getContent('romancoins', 
                $this->_getParam('slug'));
    }
}