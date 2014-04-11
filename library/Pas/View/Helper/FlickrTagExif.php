<?php


class Pas_View_Helper_FlickrTagExif extends Zend_View_Helper_Abstract {
	
	
	public function FlickrTagExif($exif) {
	$html = '';
	foreach($exif as $e){
	$html .= '<li>' . $e->label . ':' . $e->raw . '</li>';
	}
	return $html;
	}
	
}

