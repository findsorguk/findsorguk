<?php
/**
 *
 * @author dpett
 * @version 
 */

/**
 * StatGenerator helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_StatGenerator extends Zend_View_Helper_Abstract{
	
	public function statGenerator($stats) {
		if(is_array($stats)){
			return $this->view->partial('partials/database/statSearch.phtml',$stats);
		}
	}
	
}

