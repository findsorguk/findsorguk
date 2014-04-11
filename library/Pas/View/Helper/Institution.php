<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * Institution helper
 *
 * @uses viewHelper Pas_View_Helper
 * 
 */
class Pas_View_Helper_Institution extends Zend_View_Helper_Abstract{

	
	public function institution($inst) {
		if(!is_null($inst)){
		$institutions = new Institutions();
		$institution = $institutions->fetchRow($institutions->select()->where('institution = ?',$inst));
		if(!is_null($institution)){
			return $institution->description;
		} 
		} else {
		return 'The Portable Antiquities Scheme';
		}
	}
}

