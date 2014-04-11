<?php


class Pas_View_Helper_FlickrPerms extends Zend_View_Helper_Abstract {
	
	
	public function flickrperms($perms) {
	$html = '';
	foreach($perms as $k => $v){	
	if($v == 1 ){
	$html .= ucfirst(str_replace('can','',$k)) . ' '; 
	}
	}
	return $html;
	}
}

