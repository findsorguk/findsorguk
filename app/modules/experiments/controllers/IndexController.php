<?php
/** A controller for running experiments with code
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @version 1
 * @uses Pas_Yql_Oauth
 * @uses Zend_Registry
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Experiments_IndexController extends Pas_Controller_Action_Admin {

    /** The oauth object
     * @access protected
     * @var \Pas_Yql_Oauth
     */
    protected $_oauth;
	
    /** Init the controller
     * @access public
     * @return void
     */
    public function init() {
        $this->_helper->_acl->allow('public',NULL);
    	$this->_oauth = new Pas_Yql_Oauth();  
    }
    
    /** The default action
     * @access public
     * @return void
     */
    public function indexAction() {
        //Nothing doing yet
    }
}

