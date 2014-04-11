<?php
/** A class for creating a new latitude/longitude pair at a distance from a point.
 * Bearing is in degrees 
 * @category Pas
 * @package Pas_Geo
 * @license GNU public
 * @version 1
 * @since 25/2/2012
 * @author Daniel Pett
 * 
 *  Example usage:
 *  $geo = new Pas_Geo_BoundBox();
 *  $newPoint = $geo->createPoint(53.1,-2.0, 45, null,true); 
 *  If creating a square use bearings of 45, 135, 225 and 315 degrees
 */
class Pas_Geo_BoundBox {
	
	/** Create the new point
	 * @access public
	 * @param float $latitude
	 * @param float $longitude
	 * @param int $bearing
	 * @param int $distance
	 * @param string $distance_unit
	 * @param boolean $resultsAsArray
	 * @return array|string
	 * @todo Put in validator for lat long values
	 */
	public function createPoint($latitude, $longitude, $bearing, $distance, $distance_unit = "km", 
		$resultsAsArray = FALSE) {
  	//Note "m" is not metres it is imperial miles
    if ($distance_unit == "m") {
		  $radius = 3963.1676;
    }
    else {
      // distance
      $radius = 6378.1;
    }
  
    //	New latitude in degrees.
    $new_latitude = rad2deg(asin(sin(deg2rad($latitude)) * cos($distance / $radius) 
    + cos(deg2rad($latitude)) * sin($distance / $radius) * cos(deg2rad($bearing))));
    		
    //	New longitude in degrees.
    $new_longitude = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad($bearing)) 
    * sin($distance / $radius) * cos(deg2rad($latitude)), cos($distance / $radius) - sin(deg2rad($latitude)) 
    * sin(deg2rad($new_latitude))));
    
    if ($resultsAsArray) {
      //  Assign new latitude and longitude to an array to be returned to the caller.
      $coord = array();
      $coord['lat'] = $new_latitude;
      $coord['lng'] = $new_longitude;
    }
    else {
      $coord = $new_latitude . "," . $new_longitude;
    }
    
    return $coord;
  
  }
}

