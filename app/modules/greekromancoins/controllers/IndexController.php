<?php
/** Controller for displaying index page for Greek and Roman provincial world coins
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Content
*/
class GreekRomanCoins_IndexController extends Pas_Controller_Action_Admin {
	
    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()  {
 	$this->_helper->_acl->allow(null);
    }
    
    /** Internal period number
     * @access protected   
     * @var integer
     */
    protected $_period = 66;
	
    /** Set up the index display pages
     * @access public
     * @return void
     */
    public function indexAction()  {
	$content = new Content();
	$this->view->content =  $content->getFrontContent('greekromancoins');    
    }
}