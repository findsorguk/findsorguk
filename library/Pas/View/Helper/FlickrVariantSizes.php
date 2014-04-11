<?php


class Pas_View_Helper_FlickrVariantSizes extends Zend_View_Helper_Abstract {
	
	
	public function flickrVariantSizes($sizes) {
	$sizesNew = array();
	foreach($sizes as $k){
	$sizesNew[$k->label] = $k->url; 
	}
	$links = array();
	foreach($sizesNew as $k => $v) {
		$links[] = '<a href="' .  $v . '" title="View different size on flickr">' . $k  . '</a>';
	}
	$html = implode(' | ', $links);
	return $html;
	}
	
}

