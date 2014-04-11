<?php
/**
 * A view helper for returning the number of new sign ups recently
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see 	   Zend_View_Helper_Abstract
 * @author 	   Daniel Pett
 */
class Pas_View_Helper_NewPeople extends Zend_View_Helper_Abstract {
	
	/** Get a list of new people from model
	 * @access private
	 * @return array 
	 */
	private function getNew() {
	$users = new Users();
	return 	$count = $users->newPeople();
	}
	
	/** Get new people and build html
	 * @access private
	 * @return function buildhtml 
	 */
	public function newPeople() {
	$people = $this->getNew();
	if(count($people) > 0) {
	return $this->buildHtml($people);	
	} else {
	return null;
	}
	}
	
	/** Build the HTML
	 * @access public 
	 * @param array $people An array of people's usernames
	 */
	private function buildHtml($people)	{
	$html = '';
	$html .= '<h4>Welcome to today\'s new joiners</h4>';
	$html .= '<ul>';
	foreach($people as $v) {
	$url = $this->view->url(array('module' => 'users','controller' => 'named','action' => 'person','as' => $v['username']),NULL,true);
	$html .= '<li><a href="' . $url .'" title="View account details for ' . $v['username'] . '">' . $v['username'] . '</a></li>';	
	}
	$html .= '</ul>';
	return $html;	
	}	
}

