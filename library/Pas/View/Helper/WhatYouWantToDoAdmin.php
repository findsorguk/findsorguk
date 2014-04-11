<?php

/**
 * A view helper for displaying optional task widgets on login pages when admin
 * 
 * @category   Pas
 * @package    Service
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_RecordEditDeleteLinks
 */

class Pas_View_Helper_WhatYouWantToDoAdmin extends Zend_View_Helper_Abstract {

	/** Get the user's role
	 * @access public
	 * @return string $role role of the user 
	 */
	public function getRole() {
	$auth = Zend_Auth::getInstance();
	if($auth->hasIdentity()) {
	$user = $auth->getIdentity();
	$role = $user->role;
	} else {
	$role = 'public';
	}	
	return $role;
	}
	
	protected $_restricted = array('flos');
	protected $_higherLevel = array('admin','fa');

	/** Build the Html
	 * @access public
	 * @return html $this->buildHtml();
	 */
	public function WhatYouWantToDoAdmin() {
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
	if(in_array($this->getRole(),$this->_higherLevel)) {
	$html .= $this->AdminAcct();
	$html .= $this->AdminContent();
	$html .= $this->buildNumismatics();
	$html .= $this->suggestResearch();
	$html .= $this->manageResearch();
	$html .= $this->buildErrors();
	$html .= $this->view->Applicants();
	$html .= $this->buildSearch();
	$html .= $this->buildMessages();
	}
	$html .= $this->buildAddRecord();
	$html .= $this->buildNews();
	$html .= $this->buildVacancy();
	$html .= $this->buildEvent();

	$html .= '</ul>';
	$html .= '</div><div id="clear"></div>';
	
	return $html;
	}

	/** Build the admin widget
	 * @access public
	 * @return string
	 */
	public function AdminAcct() {
	$USERSURL = $this->view->url(array('module' => 'admin','controller' => 'users'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="purple">';
	$newshtml .= '<a href="' . $USERSURL . '" title="Administer users">User accounts</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	/** Build the research widget
	 * @access public
	 * @return string
	 */	
	public function suggestResearch() {
	$RESEARCHSURL = $this->view->url(array('module' => 'admin','controller' => 'research','action' => 'suggested'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="black">';
	$newshtml .= '<a href="' . $RESEARCHSURL . '" title="Administer suggested topics">Suggest research</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	/** Build the management widget
	 * @access public
	 * @return string
	 */	
	public function manageResearch() {
	$RESEARCHSURL = $this->view->url(array('module' => 'admin','controller' => 'research'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="black">';
	$newshtml .= '<a href="' . $RESEARCHSURL . '" title="Administer current and past projects">Manage research projects</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	/** Build the admin content widget
	 * @access public
	 * @return string
	 */	
	public function AdminContent() {
	$CONTENTSURL = $this->view->url(array('module' => 'admin','controller' => 'content'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="grey">';
	$newshtml .= '<a href="' . $CONTENTSURL . '" title="Administer static content">Static content</a>';
	$newshtml .= '</li>';
	return $newshtml;
	
	}
	/** Build the numismatics widget
	 * @access public
	 * @return string
	 */	
	public function buildNumismatics() {
	$NUMISMATICSURL = $this->view->url(array('module' => 'admin','controller' => 'numismatics'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="orange">';
	$newshtml .= '<a href="' . $NUMISMATICSURL . '" title="Administer numismatic content">Coin guides</a>';
	$newshtml .= '</li>';
	return $newshtml;
	
	}
	/** Build the events widget
	 * @access public
	 * @return string
	 */	
	public function buildEvent() {
	$EVENTSURL = $this->view->url(array('module' => 'admin','controller' => 'events','action' => 'add'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="green">';
	$newshtml .= '<a href="' . $EVENTSURL . '" title="Add an event">Add an event</a>';
	$newshtml .= '</li>';
	return $newshtml;
	
	}
	/** Build the add record widget
	 * @access public
	 * @return string
	 */	
	public function buildAddRecord() {
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
	public function buildNews()	{
	$NEWSURL = $this->view->url(array('module' => 'admin','controller' => 'news','action' => 'add'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="red">';
	$newshtml .= '<a href="' . $NEWSURL . '" title="Add a news story">Add a news story</a>';
	$newshtml .= '</li>';
	return $newshtml;
	
	}
	/** Build the vacancy widget
	 * @access public
	 * @return string
	 */	
	public function buildVacancy() {
	$NEWSURL = $this->view->url(array('module' => 'admin','controller' => 'vacancies','action' => 'add'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="black">';
	$newshtml .= '<a href="' . $NEWSURL . '" title="Add a vacancy">Add a vacancy</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	/** Build the search widget
	 * @access public
	 * @return string
	 */	
	public function buildSearch() {
	$NEWSURL = $this->view->url(array('module' => 'admin','controller' => 'search'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="black">';
	$newshtml .= '<a href="' . $NEWSURL . '" title="View searches">View search log</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	
	/** Build the messages widget
	 * @access public
	 * @return string
	 */	
	public function  buildMessages() {
	$messages = new Messages();
	$mess = $messages->getCount();
	$USERSURL = $this->view->url(array('module' => 'admin','controller' => 'messages'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="purple">';
	$newshtml .= '<a href="' . $USERSURL . '" title="Administer users">' . $mess['0']['total'] . ' messages</a>';
	$newshtml .= '</li>';
	return $newshtml;
	}
	
	/** Build the errors widget
	 * @access public
	 * @return string
	 */	
	public function  buildErrors() {
	$messages = new ErrorReports();
	$mess = $messages->getCount();
	$USERSURL = $this->view->url(array('module' => 'admin','controller' => 'errors'),null,true);
	$newshtml = '';
	$newshtml .= '<li class="purple">';
	$newshtml .= '<a href="' . $USERSURL . '" title="Administer users">' . $mess['0']['total'] . ' errors</a>';
	$newshtml .= '</li>';
	return $newshtml;
	
	}
	
}
