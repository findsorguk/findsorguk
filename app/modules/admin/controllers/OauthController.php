<?php
/** Controller for administering oauth and setting up tokens
 * 
 * @category   Pas
 * @package    Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses OauthTokens
 * @uses Yahoo
 * @uses Twitter
 * @uses Pas_Oauth_Flickr
 * @uses Pas_Oauth_Google
 * @uses Pas_Exception
*/

class Admin_OauthController extends Pas_Controller_Action_Admin {
	
    /** Set up the ACL and resources
     * @access public
     * @return void
     */		
    public function init() {
	$this->_helper->_acl->allow('admin',null);
        
    }

    /** List available Oauth tokens that have been generated for use.
     * @access public
     * @return void 
     */
    public function indexAction() {
        $tokens = new OauthTokens();
        $this->view->tokens = $tokens->fetchAll();
    }
    
    /** Initiate request to create a yahoo token. This can only be done when 
     * logged into Yahoo
     * and also as an admin
     * @access public
     * @return void
     */	
    public function yahooAction() {
        $yahoo = new Yahoo();
        $this->_redirect($yahoo->request());
    }
    
    /** Initiate request to create a yahoo token. This can only be done when 
     * logged into Yahoo
     * and also as an admin
     * @return void
     * @access public
    */	
    public function yahooaccessAction(){
	$yahoo = new Yahoo();
	$yahoo->access();
	$this->getFlash()->addMessage('Token created');
	$this->_redirect('/admin/oauth/');
    }
    /** Initiate request to create a twitter request token. This can only be 
     * done when logged into twitter
     * and also as an admin
     * @access public
     * @return void
     */	
    public function twitterAction(){
        $twitter = new Twitter();
        $this->_redirect($twitter->request());
    }
    /** Initiate request to create a twitter access token. This can only be 
     * done when logged into twitter
     * and also as an admin
     * @access public
     * @return void
     * @throws Pas_Yql_Exception
     */
    public function twitteraccessAction(){
        $twitter = new Twitter();
        $twitter->access();
        if(isset($twitter)){
            $this->getFlash()->addMessage('Token created');
            $this->_redirect('/admin/oauth/');
        } else {
            throw new Pas_Yql_Exception('Token creation failed', 500);
        }
    }

    /** Generate token from flickr oauth
     * @access public
     * @return void
     */
    public function flickrAction(){
        $flickr = new Pas_Oauth_Flickr();
        $flickr->setCallback('/admin/oauth/flickraccess');
        $flickr->setConsumerKey($this->_helper->config()
                ->webservice->flickr->apikey);
        $flickr->setConsumerSecret($this->_helper->config()
                ->webservice->flickr->secret);
        $flickr->generate();
    }

    /** Get a flickr access token
     * @access public
     * @return void
     * @throws Pas_Exception
     */
    public function flickraccessAction(){
        $flickr = new Pas_Oauth_Flickr();
        $flickr->setCallback('/admin/oauth/flickraccess');
        $flickr->setConsumerKey($this->_helper->config()
                ->webservice->flickr->apikey);
        $flickr->setConsumerSecret($this->_helper->config()
                ->webservice->flickr->secret);
        $access = $flickr->access();
        if($access) {
            $this->getFlash()->addMessage('Token created');
            $this->_redirect('/admin/oauth/');
        } else {
            throw new Pas_Exception('Token creation failure', 500);
        }
    }

    /** Generate a Google Oauth token
     * @access public
     * @return void
     */
    public function googleAction() {
        $google = new Pas_Oauth_Google();
        $google->setCallback('/admin/oauth/googleaccess');
        $google->setConsumerKey($this->_helper->config()
                ->webservice->google->oauthconsumerkey);
        $google->setConsumerSecret($this->_helper->config()
                ->webservice->google->oauthsecret);
        $google->generate();	
    }

    /** Get an access token
     * @access public
     * @return void
     * @throws Pas_Exception
     */
    public function googleaccessAction(){
        $google = new Pas_Oauth_Google();
        $google->setCallback('/admin/oauth/googleaccess');
        $google->setConsumerKey($this->_helper->config()
                ->webservice->google->oauthconsumerkey);
        $google->setConsumerSecret($this->_helper->config()
                ->webservice->google->oauthsecret);
        $access = $google->access();
        if($access) {
        $this->getFlash()->addMessage('Token created');
        $this->_redirect('/admin/oauth/');	
        } else {
            throw new Pas_Exception('Token creation failure', 500);
        }
    }
}