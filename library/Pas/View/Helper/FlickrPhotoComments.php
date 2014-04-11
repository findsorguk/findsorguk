<?php


class Pas_View_Helper_FlickrPhotoComments extends Zend_View_Helper_Abstract {
	
	
	public function FlickrPhotoComments($comments) {
	$html = '';	
	unset($comments->comments->photo_id);
	foreach($comments->comments as $c){
	$html .= '<li><img src="http://farm' .$c->iconfarm .'.static.flickr.com/' . $c->iconserver . '/buddyicons/' . $c->author . '.jpg" alt="'  
	.  $c->author . '\'s buddy icon" height="48" width="48" style="float:right;"/>'. $c->content 
	. '<br /> Created: ' . date(DATE_ATOM, $c->datecreate) . ' by <a href="http://www.flickr.com/photos/' 
	. $c->author . '" title="View ' . $c->authorname . ' on Flickr">' . $c->authorname . '</a><br /><a href="' .
	$c->permalink . '" title="View the comment in context">Permalink</a></li>';
	} 
	return $html;
	}
	
}

