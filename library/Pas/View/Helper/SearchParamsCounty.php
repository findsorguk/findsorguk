<?php
 class Pas_View_Helper_SearchParamsCounty extends Zend_View_Helper_Abstract
 {
 
function SearchParamsCounty($params)
	{
if(array_key_exists('county',$params)) {
if($params['county'] != NULL) {
echo 'var geoXml = new GGeoXml("http://www.finds.org.uk/kml/'.str_replace(' ','',$params['county']).'.kml");';
echo 'map.addOverlay(geoXml);';
}
}

	}


}