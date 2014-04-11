<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmailSaveSearch
 *
 * @author danielpett
 */
class Pas_View_Helper_EmailSaveSearch extends Zend_View_Helper_Abstract{

    protected $_identity;

    protected $_allowed = array('member', 'flos', 'admin', 'treasure', 'hero', 'fa' );

    public function __construct() {
    $person = new Pas_User_Details();
	$details = $person->getPerson();
	if($details){
	$this->_identity = $details->role;
	} else {
		$this->_identity = 'public';
	}
    }

    protected function buildHtml(){
        $simple = '<a href="' . $this->view->url(array(
            'module' => 'database',
            'controller' => 'search'),
	'default',true) . '">Back to simple search</a>';
        $advanced = '<a href="' . $this->view->url(array(
            'module' => 'database',
            'controller' => 'search',
            'action' => 'advanced'),
	'default',true) . '">Back to advanced search</a>';
        $email = '<a href="' . $this->view->url(array(
            'module' => 'database',
            'controller' => 'search',
            'action' => 'email'),
	'default',true) . '">Send this search to someone</a>';
        $save = '<a href="' . $this->view->url(array(
            'module' => 'database',
            'controller' => 'search',
            'action' => 'save'),
	'default',true) . '">Save this search</a>';

        if(in_array($this->_identity, $this->_allowed)){
        $urls = array($simple, $advanced, $email, $save);
        } else {
            $urls = array($simple, $advanced);
        }
        $html = '<p>' . implode(' | ', $urls);
        return $html;
    }


    public function emailSaveSearch(){
        return $this->buildHtml();
    }


}

