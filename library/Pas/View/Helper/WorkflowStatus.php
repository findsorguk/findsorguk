<?php
/**
 * A view helper for displaying workflow as a textual representation
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_WorkflowStatus extends Zend_View_Helper_Abstract {
	/** Determine the workflow word based on lookup
	 * 
	 * @param integer $secwfstage
	 */
	
	public function workflowStatus($secwfstage) {
		switch($secwfstage)
		{
			case ($secwfstage == 1):
				$status = 'Quarantine';
				break;
			case($secwfstage == 2):
				$status = 'On review';
				break;
			case($secwfstage == 4):
				$status = 'Awaiting validation';
				break;
			case($secwfstage == 3):
				$status = 'Published';
				break;	
		}
		return $status;
	}
}