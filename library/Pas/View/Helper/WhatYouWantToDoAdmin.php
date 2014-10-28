<?php

/**
 * A view helper for displaying optional task widgets on login pages when admin
 *
 * @category   Pas
 * @package    View_Helper
 * @subpackage WhatYouWantToDoAdmin
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_View_Helper_RecordEditDeleteLinks
 */

class Pas_View_Helper_WhatYouWantToDoAdmin extends Zend_View_Helper_Abstract {
    
    protected $_restricted = array('flos');
    
    protected $_higherLevel = array('admin','fa');
    
    /** Get the user's role
     * @access public
     * @return string $role role of the user
     */
    public function getRole() {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $user = $auth->getIdentity();
            $role = $user->role;
        } else {
            $role = 'public';
        }
        return $role;
    }

    

    /** Build the Html
     * @access public
     * @return html $this->buildHtml();
     */
    public function WhatYouWantToDoAdmin() {
        return $this;
    }
    
    public function __toString() {
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
        if (in_array($this->getRole(),$this->_higherLevel)) {
            $html .= $this->adminAcct();
            $html .= $this->adminContent();
            $html .= $this->buildNumismatics();
            $html .= $this->suggestResearch();
            $html .= $this->manageResearch();
            $html .= $this->buildErrors();
            $html .= $this->view->applicants();
            $html .= $this->buildMessages();
        }
        $html .= $this->buildAddRecord();
        $html .= $this->buildNews();
        $html .= $this->buildVacancy();
        $html .= $this->buildEvent();
        $html .= '</ul>';
        $html .= '</div>';
        return $html;
    }

    /** Build the admin widget
     * @access public
     * @return string
     */
    public function adminAcct() {
        $urlUsers = $this->view->url(array(
            'module' => 'admin',
            'controller' => 'users',
            'action' => 'index'
            ),'default',true);
        $html = '';
        $html .= '<li >';
        $html .= '<a href="';
        $html .= $urlUsers;
        $html .= '" title="Administer users">User accounts</a>';
        $html .= '</li>';
        return $html;
    }
    
    /** Build the research widget
     * @access public
     * @return string
     */
    public function suggestResearch() {
        $suggest = $this->view->url(array(
            'module' => 'admin',
            'controller' => 'research',
            'action' => 'suggested'),null,true);
        $html = '';
        $html .= '<li class="black">';
        $html .= '<a href="';
        $html .= $suggest;
        $html .= '" title="Administer suggested topics">Suggest research</a>';
        $html .= '</li>';
        return $html;
    }
    
    /** Build the management widget
     * @access public
     * @return string
     */
    public function manageResearch() {
        $research = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'research')
                ,null,true);
        $html = '';
        $html .= '<li class="black">';
        $html .= '<a href="';
        $html .= $research;
        $html .= '" title="Administer current and past projects">Manage research projects</a>';
        $html .= '</li>';
        return $html;
    }
    
    /** Build the admin content widget
     * @access public
     * @return string
     */
    public function AdminContent() {
        $content = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'content')
                ,null,true);
        $html = '';
        $html .= '<li class="grey">';
        $html .= '<a href="';
        $html .= $content; 
        $html .= '" title="Administer static content">Static content</a>';
        $html .= '</li>';
        return $html;
    }
   
    /** Build the numismatics widget
     * @access public
     * @return string
     */
    public function buildNumismatics() {
        $numismatics = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'numismatics')
                ,null,true);
        $html = '';
        $html .= '<li class="orange">';
        $html .= '<a href="';
        $html .=  $numismatics;
        $html .= '" title="Administer numismatic content">Coin guides</a>';
        $html .= '</li>';
        return $html;
    }
    
    /** Build the events widget
     * @access public
     * @return string
     */
    public function buildEvent() {
        $events = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'events',
                    'action' => 'add')
                ,null,true);
        $html = '';
        $html .= '<li class="green">';
        $html .= '<a href="';
        $html .= $events;
        $html .= '" title="Add an event">Add an event</a>';
        $html .= '</li>';
        return $html;

    }
    /** Build the add record widget
     * @access public
     * @return string
     */
    public function buildAddRecord() {
        $add = $this->view->url(
                array(
                    'module' => 'database',
                    'controller' => 'artefacts',
                    'action' => 'add')
                ,null,true);
        $html = '';
        $html .= '<li class="blue">';
        $html .= '<a href="';
        $html .= $add;
        $html .= '" title="Add a new object">Add a new object</a>';
        $html .= '</li>';
        return $html;
    }

    /** Build the news widget
     * @access public
     * @return string
     */
    public function buildNews() {
        $news = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'news',
                    'action' => 'add')
                ,null,true);
        $html = '';
        $html .= '<li class="red">';
        $html .= '<a href="';
        $html .= $news;
        $html .= '" title="Add a news story">Add a news story</a>';
        $html .= '</li>';
        return $html;

    }
    /** Build the vacancy widget
     * @access public
     * @return string
     */
    public function buildVacancy() {
        $vacancy = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'vacancies',
                    'action' => 'add')
                ,null,true);
        $html = '';
        $html .= '<li class="black">';
        $html .= '<a href="';
        $html .= $vacancy;
        $html .= '" title="Add a vacancy">Add a vacancy</a>';
        $html .= '</li>';
        return $html;
    }
    

    /** Build the messages widget
     * @access public
     * @return string
     */
    public function buildMessages() {
        $messages = new Messages();
        $mess = $messages->getCount();
        $message = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'messages')
                ,null,true);
        $html = '';
        $html .= '<li class="purple">';
        $html .= '<a href="';
        $html .= $message;
        $html .= '" title="Administer users">';
        $html .= $mess['0']['total'];
        $html .= ' messages</a>';
        $html .= '</li>';
        return $html;
    }

    /** Build the errors widget
     * @access public
     * @return string
     */
    public function buildErrors() {
        $messages = new ErrorReports();
        $mess = $messages->getCount();
        $errors = $this->view->url(
                array(
                    'module' => 'admin',
                    'controller' => 'errors')
                ,null,true);
        $html = '';
        $html .= '<li class="purple">';
        $html .= '<a href="';
        $html .= $errors;
        $html .= '" title="Administer users">';
        $html .= $mess['0']['total'];
        $html .= ' errors</a>';
        $html .= '</li>';
        return $html;
    }
}