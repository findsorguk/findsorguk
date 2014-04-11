<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * GenerateSecuID Action Helper 
 * 
 */
class Pas_Controller_Action_Helper_GenerateSecuID 
		extends Zend_Controller_Action_Helper_Abstract {
	
	const  DBASE_ID = 'PAS';

	/**The secure ID instance
	 * 
	 */
	const  SECURE_ID = '001';
	
	/**
	 * Strategy pattern: call helper as broker method
	 */
	public function direct() {
		return $this->_secuid();
	}
	
	protected function _secuid() {
	list($usec, $sec) = explode(" ", microtime());
	$ms = dechex(round($usec * 4080));
	while(strlen($ms) < 3) {
	$ms = '0' . $ms; 
	}
	$secuid = strtoupper(self::DBASE_ID . dechex($sec) . self::SECURE_ID . $ms);
	while(strlen($ms)<3) {
	$ms = '0' . $ms; 
	}
	$secuid=strtoupper(self::DBASE_ID . dechex($sec) . self::SECURE_ID . $ms);
	return $secuid;
	}
}

