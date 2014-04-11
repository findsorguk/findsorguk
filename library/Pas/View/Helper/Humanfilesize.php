<?php
/** View helper for turning bytes to filesize in human format 
 * @version 1
 * @author Daniel Pett
 * @license GNU
 * @since September 28 2011
 * @category Pas
 * @package Pas_View_Helper
 * @subpackage Abstract
 * @uses Pas_Service_Geo_Geoplanet
 * @uses Pas_View_Helper_YahooGeoAdjacent
 */
class Pas_View_Helper_Humanfilesize 
	extends Zend_View_Helper_Abstract {

	/** Function to turn bytes into human readable value
	 * @param int $size
	 */
	public function humanfilesize($size) {
	$mod = 1024;
    $units = explode(',','B,KB,MB,GB,TB,PB');
    for ($i = 0; $size > $mod; $i++) {
    $size /= $mod;
    }
    return round($size, 2) . ' ' . $units[$i];
	}
}