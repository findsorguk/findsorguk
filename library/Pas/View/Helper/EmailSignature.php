<?php
/**
 *
 * @author dpett
 * @version
 */

/**
 * EmailSignature helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_EmailSignature extends Zend_View_Helper_Abstract {

	/**
	 * @var Zend_View_Interface
	 */
	public function user(){
		$user = new Pas_User_Details();
		return $user->getPerson()->fullname;
	}

	public function timeStampW3C(){
	$date = new Zend_Date();
	return $date->get(Zend_Date::W3C);
	}
	/**
	 *
	 */
	public function emailSignature() {
	$html = '<p>Sent by: ' . $this->view->escape($this->user()) . ' at ';
	$html .= $this->timeStampW3C() . '</p>';
	return $html;
	}

}

