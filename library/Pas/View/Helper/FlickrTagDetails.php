<?php


class Pas_View_Helper_FlickrTagDetails extends Zend_View_Helper_Abstract {
	
	
	public function flickrtagDetails($tags) {
	$tagsNew = array();
	foreach($tags as $t ){
		if(is_object($t)){
	$tagsNew[] = '<a title="View all photos we have tagged as ' . $t->content . '" href="' 
	. $this->view->url(array('module' => 'flickr','controller' => 'photos','action' => 'tagged',
	'as' => $t->content),'default',true) . '">' . $t->content . '</a>';
		}	
	}
	$html = implode(', ', $tagsNew);
	return $html;
	}
	
}

