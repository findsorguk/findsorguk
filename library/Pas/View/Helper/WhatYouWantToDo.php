<?php
/**
 * A view helper for displaying optional task widgets on login pages
 * 
 * @category   Pas
 * @package    Service
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_RecordEditDeleteLinks
 */

class Pas_View_Helper_WhatYouWantToDo extends Zend_View_Helper_Abstract {

	/** Get the user's role
	 * @access public
	 * @return string $role role of the user 
	 */
	public function getRole(){
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()){
	$user = $auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	protected $_restricted = array('public','member','hero','research');
	protected $_upgrades = array('public','member');

	/** Build the Html
	 * @access public
	 * @return html $this->buildHtml();
	 */
	public function WhatYouWantToDo(){
		return $this->buildHtml();
	}

	/** Build the html
	 * @access public
	 * 
	 */
	public function buildHtml() {
	$html = '';
	$html .= '<div id="action">';
	$html .= '<ul>';
	$html .= $this->buildDatabase();
	$html .= $this->buildAcct();
	$html .= $this->buildAddRecord();	
	if(!in_array($this->getRole(),$this->_restricted)) {
	$html .= $this->buildStaffProfile();
	$html .= $this->buildStaffImage();
	$html .= $this->buildNews();
	$html .= $this->buildEvent();
	$html .= $this->buildStaffLogo();
	}
	$html .= $this->buildPassword();
	if(in_array($this->getRole(),$this->_upgrades)) {
	$html .= $this->buildUpgrade();
	}
	$html .= '</ul>';
	$html .= '</div><div id="clear"></div>';
	return $html;
	}

	/** Build the database widget
	 * @access public
	 * @return string
	 */
	public function buildDatabase(){
	$DBASEURL = $this->view->url(array('module' => 'database'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="green">';
	$newshtml .= '<a href="' . $DBASEURL . '" title="Edit profile">Search our database</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	
	/** Build the upgrade widget
	 * @access public
	 * @return string
	 */
	public function buildUpgrade(){
	$DBASEURL = $this->view->url(array('module' => 'users','controller' => 'account','action' => 'upgrade'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="green">';
	$newshtml .= '<a href="' . $DBASEURL . '" title="Edit profile">Request account upgrade</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	
	/** Build the account widget
	 * @access public
	 * @return string
	 */
	public function buildAcct(){
	$NEWSURL = $this->view->url(array('module' => 'users','controller' => 'account','action' => 'edit'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="purple">';
	$newshtml .= '<a href="' . $NEWSURL . '" title="Edit profile">Edit account</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}


	/** Build the event widget
	 * @access public
	 * @return string
	 */
	public function buildEvent(){
	$NEWSURL = $this->view->url(array('module' => 'users','controller' => 'events','action' => 'add'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="green">';
	$newshtml .= '<a href="' . $NEWSURL . '" title="Add an event">Add an event</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}

	/** Build the add record widget
	 * @access public
	 * @return string
	 */
	public function buildAddRecord(){
	$ADDSURL = $this->view->url(array('module' => 'database','controller' => 'artefacts','action' => 'add'),null,true);
	$addhtml = '';
	$addhtml .= '<li class="blue">';
	$addhtml .= '<a href="' . $ADDSURL . '" title="Add a new object">Add a new object</a>';
	$addhtml .= '</li>';
	return $addhtml;
	}
	
	/** Build the news widget
	 * @access public
	 * @return string
	 */	
	public function buildNews() {
	$NEWSURL = $this->view->url(array('module' => 'users','controller' => 'news','action' => 'add'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="red">';
	$newshtml .= '<a href="' . $NEWSURL . '" title="Add a news story">Add a news story</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}

	/** Build the change password widget
	 * @access public
	 * @return string
	 */
	public function buildPassword() {
	$PWORDURL = $this->view->url(array('module' => 'users','controller' => 'account','action' => 'changepassword'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="blue">';
	$newshtml .= '<a href="'.$PWORDURL . '" title="Change your password">Change password</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	
	/** Build staff profile widget
	 * @access public
	 * @return string
	 */
	public function buildStaffProfile(){
	$PWORDURL = $this->view->url(array('module' => 'users','controller' => 'profile','action' => 'edit'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="blue">';
	$newshtml .= '<a href="' . $PWORDURL . '" title="Edit staff profile">Edit staff profile</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	
	/** Build staff image widget
	 * @access public
	 * @return string
	 */
	public function buildStaffImage(){
	$PWORDURL = $this->view->url(array('module' => 'users','controller' => 'profile','action' => 'image'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="green">';
	$newshtml .= '<a href="' . $PWORDURL . '" title="Change profile image">Staff profile image</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	
	/** Build staff logo widget
	 * @access public
	 * @return string
	 */
	public function buildStaffLogo() {
	$PWORDURL = $this->view->url(array('module' => 'users','controller' => 'profile','action' => 'logo'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="purple">';
	$newshtml .= '<a href="' . $PWORDURL . '" title="Change staff institution logo">Partner logos</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
}
