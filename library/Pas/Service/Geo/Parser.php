<?php
/**
* A class for parsing geo data from Yahoo geoplanet
*
* @category   Pas
* @package    service
* @subpackage Geo
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License
* @author 	  Daniel Pett

*/
class Pas_Service_Geo_Parser {
	
	public function parsePlaceFromList($place)  {
    if(!is_null($place)) {
    $placeData = array();
   	foreach($place->place as $p) {
   	if($p->country){
    $country = (string) $p->country->content;
    } else {
  	$country = NULL;	
  	}
    if($p->admin1) {
    $admin1 = (string) $p->admin1->content;
    } else {
  	$admin1 = NULL;	
  	}
    if($p->admin2){
    $admin2 = (string) $p->admin2->content;
    } else {
  	$admin2 = NULL;	
  	}
    if($p->admin3){
    $admin3 = (string) $p->admin3->content;
    } else {
  	$admin3 = NULL;	
  	}
  	if($p->locality1){
    $locality1 = (string) $p->locality1->content;
  	} else {
  	$locality1 = NULL;	
  	}
  	if($p->locality2){
  	$locality2 = (string) $p->locality2->content;
  	} else {
  	$locality2 = NULL;	
  	}
  	if($p->postal){
  	$postal = $p->postal->content;	
  	}
  	$centroid = array(
    'lat' => (string) $p->centroid->latitude,
    'lng' => (string) $p->centroid->longitude
    );
    $bb =  array('southWest' => array(
    'lat' => (string) $p->boundingBox->southWest->latitude, 
    'lng' => (string) $p->boundingBox->southWest->longitude),
    'northEast' => array(
    'lat' => (string) $p->boundingBox->northEast->latitude,
    'lng' => (string) $p->boundingBox->northEast->longitude)
    );
    $placeData[] = array(
    'woeid' => (string) $p->woeid,
    'placeTypeName' =>  (string) $p->placeTypeName->content,
    'name' => (string) $p->name,
    'country' => $country,
    'admin1' => $admin1,
    'admin2' => $admin2,
    'locality1' => $locality1,
    'locality2' => $locality2,
    'postal' =>  $postal,
    'latitude' => $p->centroid->latitude,
    'latitude' => $p->centroid->latitude,
    'centroid' => $centroid,
    'boundingBox' => $bb
    );
   	}
    return $placeData;
    } else {
    $placeData = NULL;	
    return $placeData;
    }
    }
	
    public function parsePlace($place) {
    $place = $place->query->results->place;
    $placeData = array();
    $placeData['woeid'] = (string) $place->woeid;
    $placeData['placeTypeName'] = (string) $place->placeTypeName->content;
    $placeData['name'] = (string) $place->name;
    if($place->country){
    $placeData['country'] = (string) $place->country->content;
    }
    if($place->admin1) {
    $placeData['admin1'] = (string) $place->admin1->content;
    }
    if($place->admin2){
    $placeData['admin2'] = (string) $place->admin2->content;
    }
    if($place->admin3){
    $placeData['admin3'] = (string) $place->admin3->content;
    }
  	if($place->locality1){
    $placeData['locality1'] = (string) $place->locality1->content;
  	}
  	if($place->locality2){
  	$placeData['locality2'] = (string) $place->locality2->content;
  	}
  	if($place->postal){
    $placeData['postal'] = $place->postal->content;
  	}
    $placeData['latitude'] = $place->centroid->latitude;
    $placeData['longitude'] = $place->centroid->longitude;
    $placeData['centroid'] = array(
    'lat' => (string) $place->centroid->latitude,
    'lng' => (string) $place->centroid->longitude
    );
    $placeData['boundingBox'] = array('southWest' => array(
    'lat' => (string) $place->boundingBox->southWest->latitude, 
    'lng' => (string) $place->boundingBox->southWest->longitude),
	'northEast' => array(
	'lat' => (string) $place->boundingBox->northEast->latitude,
	'lng' => (string) $place->boundingBox->northEast->longitude)
	);
	return $placeData;
	}
    
	public function parseSinglePlace($place) {
    $placeData = array();
    $placeData['woeid'] = (string) $place->woeid;
    $placeData['placeTypeName'] = (string) $place->placeTypeName->content;
    $placeData['name'] = (string) $place->name;
    if($place->country){
    $placeData['country'] = (string) $place->country->content;
    }
    if($place->admin1) {
    $placeData['admin1'] = (string) $place->admin1->content;
    }
    if($place->admin2){
    $placeData['admin2'] = (string) $place->admin2->content;
    }
    if($place->admin3){
    $placeData['admin3'] = (string) $place->admin3->content;
    }
  	if($place->locality1){
    $placeData['locality1'] = (string) $place->locality1->content;
  	}
  	if($place->locality2){
  	$placeData['locality2'] = (string) $place->locality2->content;
  	}
  	if($place->postal){
    $placeData['postal'] = $place->postal->content;
  	}
    $placeData['latitude'] = $place->centroid->latitude;
    $placeData['longitude'] = $place->centroid->longitude;
    $placeData['centroid'] = array(
    'lat' => (string) $place->centroid->latitude,
    'lng' => (string) $place->centroid->longitude
    );
    $placeData['boundingBox'] = array('southWest' => array(
    'lat' => (string) $place->boundingBox->southWest->latitude, 
    'lng' => (string) $place->boundingBox->southWest->longitude),
     'northEast' => array(
     'lat' => (string) $place->boundingBox->northEast->latitude,
     'lng' => (string) $place->boundingBox->northEast->longitude)
     );
     return $placeData;
     }
     
	public function parseItAll($place){
	$count = $place->query->count;
	  if($count == NULL){
	  	return $placeData = NULL;
	  } else if($count > 1 ){
	  foreach($place->query->results as $pl){
	  	 $placeData = $this->parsePlaceFromList($pl);
	    }
	  } else if($count == 1){
	    $placeData = $this->parseSinglePlace($place);
	  }
	  
	}

	public function parseGeocoded( $place ){
	$pl = $place->query->results;
	if($pl){
	$placeData = array(); 
	foreach ($pl->Result as $key => $value) {
		$placeData[$key] = $value;
	} 
	return $placeData;
	}
	}
	
	public function parseFlickrPlace( $place ) {
	$pl = $place->query->results->rsp->places->place;
    $placeData = array(
    'woeid' => $pl->woeid,
    'name' => $pl->name,
    'placeUrl' => $pl->place_url,
    'latitude' => $pl->latitude,
    'longitude' => $pl->longitude,
    'placeType' => $pl->place_type,
    'timezone' => $pl->timezone,
    'placeTypeId' => $pl->place_type_id,
    'centroid' => array(
    'latitude' => $pl->latitude, 
    'longitude' => $pl->longitude
    ),
    'placeID' => $pl->place_id);
    return $placeData;
    }
    
	public function parsePlaces( $place ) {
	$placeData = array();
	foreach($place->query->results->matches->match as $pl) {
    $placeData[] = array(
    'woeid' => $pl->place->woeId,
    'name' => $pl->place->name,
    'latitude' => $pl->place->centroid->latitude,
    'longitude' => $pl->place->centroid->longitude,
    'placeType' => $pl->place->type,
    'centroid' => array(
    'lat' =>  $pl->place->centroid->latitude, 
    'lng' => $pl->place->centroid->longitude)
    );
    }
    return $placeData;
    }
    
    public function parseDistance( $place )  {
    $placeData = array(
    'miles' => $place->results->distance->miles,
    'kilometres' => $place->results->distance->kilometers
    );
    return $placeData;	
    }

    public function parseElevation( $place ) {
    if(sizeof($place) > 0){
    $placeData = array(
    'elevation' => $place->query->results->json->astergdem,
    'lat' => $place->query->results->json->lat,
    'lon' =>$place->query->results->json->lng,
    'method' => 'geonames:astergdem'
    );	
    return $placeData;
    } else {
    	return false;
    }
    }
}