<?php
/** Controller for administering oauth and setting up tokens
* 
* @category   Pas
* @package    Pas_Controller
* @subpackage ActionAdmin
* @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
* @license    GNU General Public License
*/

class Admin_OauthController extends Pas_Controller_Action_Admin {
	
	/** Set up the ACL and resources
	*/		
	public function init() {
	$this->_helper->_acl->allow('admin',null);
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->view->messages = $this->_flashMessenger->getMessages();
    }
    
	/** List available Oauth tokens that have been generated for use.
	*/	
    public function indexAction() {
    $tokens = new OauthTokens();
    $this->view->tokens = $tokens->fetchAll();
    }
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahooAction() {
    $yahoo = new Yahoo();
    $this->_redirect($yahoo->request());
	}
    
	/** Initiate request to create a yahoo token. This can only be done when logged into Yahoo
	 * and also as an admin
	*/	
    public function yahooaccessAction(){
	$yahoo = new Yahoo();
	$yahoo->access();
	$this->_flashMessenger->addMessage('Token created');
	$this->_redirect('/admin/oauth/');
	}
	/** Initiate request to create a twitter request token. This can only be done when logged into twitter
	* and also as an admin
	*/	
	public function twitterAction(){
	$twitter = new Twitter();
	$this->_redirect($twitter->request());
	}
	/** Initiate request to create a twitter access token. This can only be done when logged into twitter
	 * and also as an admin
	*/	
	public function twitteraccessAction(){
	$twitter = new Twitter();
	$twitter->access();
	if(isset($twitter)){
	$this->_flashMessenger->addMessage('Token created');
	$this->_redirect('/admin/oauth/');
	} else {
		throw new Pas_Yql_Exception('Token creation failed');
	}
	}
	
	public function flickrAction(){
	$flickr = new Pas_Oauth_Flickr();
	$flickr->generate();
	}
	
	public function flickraccessAction(){
	$flickr = new Pas_Oauth_Flickr();
	$access = $flickr->access();
	$this->_flashMessenger->addMessage('Token created');
	$this->_redirect('/admin/oauth/');
	}
	
	public function googleAction() {
	$google = new Pas_Oauth_Google();
	$google->generate();	
	}
	
	
	public function googleaccessAction(){
	$google = new Pas_Oauth_Google();
	$access = $google->access();
	$this->_flashMessenger->addMessage('Token created');
	$this->_redirect('/admin/oauth/');	
	}
}