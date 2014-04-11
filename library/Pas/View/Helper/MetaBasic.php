<?php
 class Zend_View_Helper_MetaBasic extends Zend_View_Helper_Abstract
 {




public function metaBasic()
	{
$date = new Zend_Date();
$date->add('72',Zend_Date::HOUR);
$this->view->headMeta()->appendHttpEquiv('expires',$date->get(Zend_Date::RFC_1123))
					   ->appendHttpEquiv('Content-Type','text/html; charset=UTF-8')
                 	   ->appendHttpEquiv('Content-Language', 'en-GB')
					   ->appendHttpEquiv('imagetoolbar', 'no');
$this->view->headMeta('Daniel Pett','DC.Creator');
$this->view->headMeta($this->view->CurUrl(),'DC.Identifier');
$this->view->headMeta($this->view->title(),'DC.Title');
$this->view->headMeta('basic,search,what,where,when,portable antiquities','DC.Keywords');
$this->view->headMeta('The Portable Antiquities Scheme and the British Museum','DC.Publisher');
$this->view->headMeta('Search the Portable Antiquities Scheme Database using our basic what where when search interface.','DC.Description');
$this->view->headMeta('','DC.date.created');
$this->view->headMeta('Archaeology','DC.subject');
	}

}