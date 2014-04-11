<?php
 /**
 *
 * @uses       Zend_Controller_Action_Helper_Abstract
 * @package    Controller
 * @subpackage Controller_Action
 * @copyright  Copyright (c) 2007,2008 Rob Allen
 * @license    http://framework.zend.com/license/new-bsd  New BSD License
 */
class Pas_Controller_Action_Helper_SegmentGa 
	extends Zend_Controller_Action_Helper_Abstract {
		
		const SEGMENT_ALL_VISITS = 1;
		const SEGMENT_NEW_VISITORS = 2;
		const SEGMENT_RETURNING_VISITORS = 3;
		const SEGMENT_PAID_SEARCH_TRAFFIC = 4; 
		const SEGMENT_NO_PAID_SEARCH_TRAFFIC = 5;
		const SEGMENT_SEARCH_TRAFFIC = 6;
		const SEGMENT_DIRECT_TRAFFIC = 7;
		const SEGMENT_REFERRAL_TRAFFIC = 8;
		const SEGMENT_VISITS_WITH_CONVERSIONS = 9;
		const SEGMENT_VISITS_WITH_TRANSACTIONS = 10;
		const SEGMENT_MOBILE_TRAFFIC = 11;
		const SEGMENT_NON_BOUNCE_VISITS = 12;
		const SEGMENT_TABLET_TRAFFIC = 13;

		protected $_view;
		
		protected $_segments = array(
			1 => 'allvisits',
    		2 => 'newvisitors',
    		3 => 'returning',
    		4 => 'paidsearch',
    		5 => 'unpaidsearch',
    		6 => 'searchtraffic',
    		7 => 'directtraffic',
    		8 => 'referredtraffic',
    		9 => 'conversions',
    		10 => 'ecommerce',
    		11 => 'mobile',
    		12 => 'nobounces',
    		13 => 'tablets'
    		);
		
		public function preDispatch()
	    {
	
		$this->_view = $this->_actionController->view;
	    }
		
		public function direct() 
		{
		return $this->handleForm();
		}
		
		public function handleForm()
		{
			Zend_Debug::dump($this->view->form);
    	exit;
		if($this->getRequest()->isPost() && $this->view->form->isValid($this->_request->getPost())){
    	if ($this->view->form->isValid($this->view->form->getValues())) {
    	$segment = $this->view->form->getValues();
    	
    	$segments = array_flip($this->_segments);
    	if(in_array($segment, $segments)){
    		$segmentConstant = $segments[$segment];
    	} else {
    		throw new Pas_Analytics_Exception('That segment does not exist');
    	}
    	return $segmentConstant;
    	} else {
    	$this->view->form->populate($segment);
    	}
		}
	}
	}
