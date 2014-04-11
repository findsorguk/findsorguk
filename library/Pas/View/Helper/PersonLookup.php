<?php
/**
 *
 * @author dpett
 * @version  1
 * @copyright Daniel Pett/ British Museum
 * @license GPL
 * @
 */

/**
 * PersonLookup helper
 *
 * @uses viewHelper Zend_View_Helper_Abstract
 * @uses viewHelper Zend_View_Helper_Url
 */
class Pas_View_Helper_PersonLookup extends Zend_View_Helper_Abstract {
	
	/** The person ID to set
	 * @var 
	 */
	protected $_personID;
	
	
	/** Lookup the person and attach a url
	 * 
	 */
	public function personLookup() {
		return $this;
	}
	
	/** Set a person ID 
	 * @access public
	 * @param $personID
	 */
	public function setPerson( $personID ) {
		if(isset($personID)){
			$this->_personID = $personID;
		}
		return $this;
	}

	/** Get the data for each person
	 * @access public
	 * 
	 */
	public function getData( ){
		if($this->_personID){
			$people = new Peoples();
			$person = $people->fetchRow($people->select()->where('secuid = ?',$this->_personID));
		return $person;
		} 
		
	}
	
	/** Create the html to render
	 * @access public
	 * 
	 */
	public function render(){
		$person = $this->getData();
		
		$html = '';
		if($person){
			$params = array('module' => 'database', 'controller' => 'people', 'action' => 'person', 'id' => $person->id);
			$url = $this->view->url($params, 'default', true);
			$html .= '<a href="' . $url . '">' . $person->fullname . '</a>';
		} else {
			$html .= 'No personal details found';
		}
		return $html;
	}
	
	/** Create the html string and return
	 * 
	 */
	public function __toString(){
		return $this->render();
	}
}

