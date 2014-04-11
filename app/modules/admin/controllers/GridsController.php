<?php

class Admin_GridsController extends Pas_Controller_Action_Admin {

 	protected $_appid;
 	protected $_geoplanet;
 	
    public function init() {
	$this->_helper->_acl->allow('admin',null);
	 $config = Zend_Registry::get('config');
        $appid = $config->ydnkeys->placemakerkey;
        $this->_appid = $appid; 
        $this->_geoplanet = new Pas_Service_Geo_Geoplanet($this->_appid);
	}

public function gridlen($gridref)
	{
	$gridref = $this->stripgrid($gridref);
	$length = strlen(str_replace(' ','',$gridref)) - 2;
	return $length;
	}


	
	
public function stripgrid($string=""){
	$stripOut = array(" ","-","-",".");
	$gridRef = str_replace($stripOut,"",$string);
	$gridRef = strtoupper($gridRef);
	return $gridRef;
}
	
	public function GetAccuracy($gridref,$clean=1){

	if ($clean == 1){$gridref = $this->stripgrid($gridref);}
	$coordCount = strlen($gridref)-2; //count length and strip off fist two characters

	switch ($coordCount) {
		case 0:
			$acc = 100000;
			break;
		case 2:
			$acc = 10000;
			break;
		case 4:
			$acc = 1000;
			break; 
		case 6:
			$acc = 100;
			break;
		case 8:
			$acc = 10;
			break;
		case 10:
			$acc = 1;
			break;
		case 12:
			$acc = 0.1;
			break;
		case 14:
			$acc = 0.01;
			break;
		default:
			return false;
			break;
	}		
	
	$gridAcc = $acc;
	return $acc;	
}
	
public function getAltitudeLatLon($Lat,$Long)
	{
	if($Lat == NULL && $Long == NULL) {
	throw new Exception('Your latitude/ longitude pair is incorrectly formed');
	} else {
	
	$config = array(
    'adapter'   => 'Zend_Http_Client_Adapter_Curl',
    'curloptions' => array(CURLOPT_POST =>  true,
						   CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
						   CURLOPT_FOLLOWLOCATION => true,
						  // CURLOPT_HEADER => false,
						   CURLOPT_RETURNTRANSFER => true,
						   ),
	);
	$args = 'lat='.$Lat;
	$args .= '&long='.$Long;
	$url = 'http://www.geomojo.org/cgi-bin/getaltitude.cgi?'.$args.'&format=json';
	
	$client = new Zend_Http_Client($url, $config);
	$response = $client->request();
	
	$data = $response->getBody();
	$json = json_decode($data);
	$altitude = $json->altitude;
	$woeid = $json->woeid;
	return array('woeid' => $woeid,'altitude' => $altitude);
	}
	
	}
public function YahooGeoAltitude($Lat,$Long)
	{
	
	return $this->getAltitudeLatLon($Lat,$Long);
	}

public function smrupdateAction()
{
	ini_set('memory_limit', '256M'); 
set_time_limit(0); 
	$this->_helper->layout->disableLayout();
	$this->view->title = "Grid update process run and complete";
	$this->_helper->layout->disableLayout();    
   $findspots = new Findspots();
   $select = $findspots->select()
   ->where('gridref IS NOT NULL')
  	->where('woeid IS NULL')
  //	->where('woeid = ?',0)
//   ->where('id >= 1')
//   ->where('id <= 1')
   ->limit(5000)
   ;
  
   $rows = $findspots->fetchAll($select);
// Zend_Debug::dump($rows);
// exit;
   // Loop through each book, grab latest sales rank, update ranks table
  foreach($rows as $row) {

  	$geo = $this->_geoplanet;
  	
      $rowid = $row->id;
      $gridref = strtoupper(str_replace(' ','',$row->gridref));
	  $fourFig = $this->FourFigure($gridref);
	   $results = $this->GridCalc($gridref);

$Lat = $results['Latitude'];
$Long = $results['Longitude'];
$easting = $results['Easting'];
$northing = $results['Northing'];	  
$tenkmap = $results['Tenk'];
$twokmap = $results['2pt5K'];
$gref = $results['Gridreference'];
$yahoo = $geo->reverseGeoCode($Lat,$Long);
$woeid = $yahoo['woeid'];
$elev = $geo->getElevation(NULL,$Lat,$Long);
$elevation = $elev['elevation'];

	//$ygeo  = $this->YahooGeoAltitude($Lat,$Long);
	//$woeid = $ygeo['woeid'];
	//$altitude = $ygeo['altitude'];
	$accuracy = $this->GetAccuracy($gridref);
	$length = $this->gridlen($gridref);
	 $data = array (
		 'gridref' => $gridref,
         'fourFigure'     => $fourFig,
		 'declong' => $Long,
		 'declat' => $Lat,
		 'map25k' => $fourFig,
		 'map10k' => $tenkmap,
         'updated'   => Zend_Date::now()->toString('yyyy-MM-dd HH:mm'),
		 'updatedBy' => '56' ,
		 'woeid' => $woeid,
		 'elevation' => $elevation,
		 'accuracy' => $accuracy,
		 'gridlen' => $length
      );
	
	$n = $findspots->update($data, 'id = '.$rowid);	

echo 'updated Row: '. $rowid .' with data ';
foreach($data as $k => $v){
echo $k .' : ' . $v . ', ';	
}
echo '<br/>';
sleep (0.5);
}
echo 'Yeah baby!';

}


public function osdataupdateAction()
{
	ini_set('memory_limit', '512M'); 
	$this->_helper->layout->disableLayout();
	$this->view->title = "Grid update process run and complete";
	$this->_helper->layout->disableLayout();    
    $findspots = new Osdata();
    $select = $findspots->select()
  					    ->where('latitude = 0')
   						->where('f_code = ?','A');
   $rows = $findspots->fetchAll($select);
   
   // Loop through each book, grab latest sales rank, update ranks table
  foreach($rows as $row) {

      $rowid = $row->id;
      $gridref = $row->km_ref;
	  
	   $results = $this->GridCalc($gridref);

$Lat = $results['Latitude'];
$Long = $results['Longitude'];

	
	
	 $data = array (
		 
		 'longitude' => $Long,
		 'latitude' => $Lat,
		 
      );
	  
	$n = $findspots->update($data, 'id = '.$rowid);	
//sleep (0.5);
}
echo 'Yeah baby!';

}


public function woeidupdateAction()
{
ini_set('memory_limit', '256M'); 
$this->view->title = "Grid update process run and complete";	
$findspots = new Findspots();
   $select = $findspots->select()
   ->where('id >= 200')
   ->where('id <= 1000')
   ->where('declong IS NOT NULL')
   ->where('declat IS NOT NULL');
$rows = $findspots->fetchAll($select);	
$place = new Pas_Service_Geo_Geoplanet($this->_appid);
	
foreach($rows as $row) {

$rowid = $row->id;
$Lat = $row->declat;
$Long = $row->declong;
$findelevation = $place->getElevation(NULL,$Lat,$Long);
$findwoeid = $place->reverseGeoCode($Lat,$Long);
$elevation = $findelevation['elevation'];
$woeid = $findwoeid['woeid'];
	 $data = array (
         'updated'   => Zend_Date::now()->toString('yyyy-MM-dd HH:mm'),
		 'updatedBy' => '56' ,
		'woeid' => $woeid,
		'elevation' => $elevation,
      );
	  
	$n = $findspots->update($data, 'id = '.$rowid);	
//sleep (0.5);
}
echo 'Yeah baby!';

}

public function generateidAction()
	{
	ini_set('memory_limit', '128M'); 
	for($i = 0; $i < 30; $i++) {
	 			list($usec,$sec)=explode(" ", microtime());
                $ms=dechex(round($usec*(4080*$i)));
                while(strlen($ms)<3) {$ms="0".$ms; }
                $secuid=strtoupper('PAS'.dechex($sec).'002'.$ms);
                while(strlen($ms)<3) {$ms="0".$ms; }
                $secuid=strtoupper('PAS'.dechex($sec).'002'.$ms);
				echo $i . ' '. $secuid;
				echo '<br />';
				
	}
	
	}
public function generatefindidAction()
	{
	ini_set('memory_limit', '128M'); 
	for($i = 0; $i < 52817; $i++) {
	list($usec,$sec)=explode(" ", microtime());
  		$suffix =  strtoupper(substr(dechex($sec),3).dechex(round($usec*8*$i)));
		$findid = 'IARCW-'.$suffix;
	echo $findid;
				echo '<br />';
				}
	}
	
	public function missingdistrictsAction(){
	$missing = new Findspots();
	$places = new Places();
	$toupdate = $missing->getMissingDistrict();
	foreach($toupdate as $k){
	$data = $places->getDistrictUpdate($k['county'],$k['parish']);
	if(array_key_exists('0',$data)){
	$rowid = $k['id'];
	$updateData = array(
	'district' => $data['0']['district'],
	'updatedBy' => 56,
	'updated' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm')
	);
	$updated = $missing->update($updateData, 'id = '.$rowid);
	Zend_Debug::dump($updated);
	}
	}
	exit;
	}
	public function gridlengthAction(){
	ini_set('memory_limit', '512M'); 
	set_time_limit(0);
	$missing = new Findspots();
	$rows = $missing->missingGrids(1000);
	foreach($rows as $r){
	$rowid = $r['id'];
	$oldData = $missing->fetchRow('id=' 
            . $rowid)->toArray();
    $where = array();
    $where[] = $missing->getAdapter()->quoteInto('id = ?', 
            $rowid);
    $insertData = $missing->updateAndProcessGrids(array('gridref' => $r['gridref']));
    $update = $missing->update($insertData, $where);
    $this->_helper->audit($insertData, $oldData, 'FindSpotsAudit',
     $rowid,$r['recordID']);
    echo 'You did it!';	
	}
	}
	
	public function fourlatlonAction(){
	ini_set('memory_limit', '512M'); 
	set_time_limit(0);
	$missing = new Findspots();
	$rows = $missing->missingfour(10000);
	foreach($rows as $r){
	$rowid = $r['id'];
	$oldData = $missing->fetchRow('id=' 
            . $rowid)->toArray();
    $where = array();
    $where[] = $missing->getAdapter()->quoteInto('id = ?', 
            $rowid);
    $insertData = $missing->updateAndProcessGrids(array('gridref' => $r['gridref']));
	$new = array(
	'geohash' => $insertData['geohash'],
	'fourFigureLat' => $insertData['fourFigureLat'],
	'fourFigureLon' => $insertData['fourFigureLon']
	);
    $update = $missing->update($new, $where);
    echo 'updated ' . $rowid . '<br />';	
	}
	echo 'You did it!';
	}
	
	public function geomuseumsAction(){
	ini_set('memory_limit', '512M'); 
	set_time_limit(0);
	$missing = new AccreditedMuseums();
	$geoCoder = new Pas_Service_Geo_Coder('AIzaSyAuDyFnm7-ea4Ls_OmCThLmL0Su42oiGNk');
	
	for($i = 0; $i < 1767; $i++) {
		sleep(1);
		$data = $missing->fetchRow('id = ' . $i);
		$where = array();
	    $where[] = $missing->getAdapter()->quoteInto('id = ?', $i);
	    $coords = $geoCoder->getCoordinates($data->museumName . ' United Kingdom');
		$updateData = array(
		'lat' => $coords['lat'],
		'lon' => $coords['lon'],
		'updatedBy' => 56
		);
		$update = $missing->update($updateData, $where);
	}
	
	}
	
	public function eastingsAction(){
	ini_set('memory_limit', '512M'); 
	set_time_limit(0);
	$missing = new Findspots();
	$rows = $missing->missingEastings(1000);
	foreach($rows as $r){
	$rowid = $r['id'];
	$oldData = $missing->fetchRow('id=' 
            . $rowid)->toArray();
    $where = array();
    $where[] = $missing->getAdapter()->quoteInto('id = ?', 
            $rowid);
    $insertData = $missing->updateAndProcessGrids(array('gridref' => $r['gridref']));
    $update = $missing->update($insertData, $where);
    $this->_helper->audit($insertData, $oldData, 'FindSpotsAudit',
     $rowid,$r['recordID']);
    echo 'You did it!';	
	}
	}
	
	public function changesourceAction(){
		$incorrect = new Findspots();
		$rows = $incorrect->incorrectSource(1000);
		foreach($rows as $r){
		$rowid = $r['id'];
		$oldData = $incorrect->fetchRow('id=' . $rowid)->toArray();
    	$where = array();
    	$where[] = $incorrect->getAdapter()->quoteInto('id = ?',$rowid);	
    	$updateData = array(
    	'updatedBy' => 56,
		'updated' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm'),
    	'gridrefsrc' => 5,
    	'comments' => 'Centred on parish by OAD, grid ref source had been incorrectly transposed.'
		);
		$update = $incorrect->update($updateData, $where);
    	$this->_helper->audit($updateData, $oldData, 'FindSpotsAudit', $rowid, $r['recordID']);
    	echo $r['recordID'] . '<br />';	
		}
		
	}

	public function elevationAction(){
		$incorrect = new Findspots();
		$rows = $incorrect->missingElevation(500);
		foreach($rows as $r){
		$rowid = $r['id'];
		$oldData = $incorrect->fetchRow('id=' . $rowid)->toArray();
    	$where = array();
    	$where[] = $incorrect->getAdapter()->quoteInto('id = ?',$rowid);	
    	$api = new Pas_Service_Geo_Elevation();
		$elevation = $api->getElevation($r['declong'], $r['declat']);
    	$updateData = array(
    	'updatedBy' => 56,
		'updated' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm'),
    	'elevation' => $elevation,
		);
		sleep(1);
		$update = $incorrect->update($updateData, $where);
    	$this->_helper->audit($updateData, $oldData, 'FindSpotsAudit', $rowid, $r['recordID']);
    	echo $r['recordID'] . '<br />';	
		}
	}
	
		public function mergefinderAction(){
		$incorrect = new Finds();
		$rows = $incorrect->getFinder($this->_getParam('oldfinder'), 500);
		foreach($rows as $r){
		$rowid = $r['id'];
		$oldData = $incorrect->fetchRow('id=' . $rowid)->toArray();
    	$where = array();
    	$where[] = $incorrect->getAdapter()->quoteInto('finderID = ?',$this->_getParam('oldfinder'));	
    	$updateData = array(
    	'updatedBy' => 56,
		'updated' => Zend_Date::now()->toString('yyyy-MM-dd HH:mm'),
    	'finderID' => $this->_getParam('newfinder'),
		);
		sleep(1);
		$update = $incorrect->update($updateData, $where);
    	$this->_helper->audit($updateData, $oldData, 'FindsAudit', $rowid, $r['recordID']);
    	echo $r['recordID'] . '<br />';	
		}
	}
	
}