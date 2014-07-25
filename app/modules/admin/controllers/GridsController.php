<?php
/** A controller for manipulating grids
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @category Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @uses Pas_Service_Geo_Geoplanet 
 * @uses Exception
 * @uses Pas_Service_Geo_Elevation
 * @uses Findspots
 * 
 */
class Admin_GridsController extends Pas_Controller_Action_Admin {

    /** The app id
     * @access protected
     * @var string
     */
    protected $_appid;
 
    /** The geo planet coder
     * @access protected
     * @var \Pas_Service_Geo_Geoplanet
     */
    protected $_geoplanet;
 	
    /** The init function
     * @access public
     * @return void
     */
    public function init() {
	$this->_helper->_acl->allow('admin',null);
        $appid = $this->_helper->config()->ydnkeys->placemakerkey;
        $this->_appid = $appid; 
        $this->_geoplanet = new Pas_Service_Geo_Geoplanet($this->_appid);
    }

    /** The grid length function
     * @access public
     * @param string $gridref
     * @return integer
     */
    public function gridlen($gridref) {
	$clean = $this->stripgrid($gridref);
	$length = strlen(str_replace(' ','', $clean)) - 2;
	return $length;
    }

    /** Strip a grid reference
     * @access public
     * @return void
     * @param string $string
     * @return string
     */
    public function stripgrid($string=""){
	$stripOut = array(" ","-","-",".");
	$gridRef = str_replace($stripOut,"",$string);
	$gridRef = strtoupper($gridRef);
	return $gridRef;
    }

    /** Get the accuracy
     * @access public
     * @param string $gridref
     * @param integer $clean
     * @return boolean|real
     */
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
            }		
	
	$gridAcc = $acc;
	return $acc;	
    }
	
    /** Get the elevation
     * @access public
     * @param double $Lat
     * @param double $Long
     * @return array
     * @throws Exception
     * 
     */
    public function getAltitudeLatLon($Lat,$Long) {
	if($Lat == NULL && $Long == NULL) {
            throw new Exception('Your latitude/ longitude pair is incorrectly formed');
	} else {
	$config = array(
            'adapter'   => 'Zend_Http_Client_Adapter_Curl',
            'curloptions' => array(
                CURLOPT_POST =>  true,
                CURLOPT_USERAGENT =>  $_SERVER["HTTP_USER_AGENT"],
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                ),
            );
	$args = 'lat=' . $Lat;
	$args .= '&long=' . $Long;
	$url = 'http://www.geomojo.org/cgi-bin/getaltitude.cgi?' 
                . $args.'&format=json';
	
	$client = new Zend_Http_Client($url, $config);
	$response = $client->request();
	
	$data = $response->getBody();
	$json = json_decode($data);
	$altitude = $json->altitude;
	$woeid = $json->woeid;
	return array('woeid' => $woeid,'altitude' => $altitude);
        }
    }
    /** The woeid update function
     * @access public
     * @return void
     */
    public function woeidupdateAction() {
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
                'updatedBy' => 56,
                'woeid' => $woeid,
                'elevation' => $elevation,
                );
            $n = $findspots->update($data, 'id = '.$rowid);	
        }
        echo 'Yeah baby!';
    }

    
    /** Set grid length where missing
     * @access public
     * @return void
     */
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
            $where[] = $missing->getAdapter()->quoteInto('id = ?', $rowid);
            $insertData = $missing->updateAndProcessGrids(
                    array('gridref' => $r['gridref'])
                    );
            $update = $missing->update($insertData, $where);
            $this->_helper->audit(
                    $insertData, 
                    $oldData, 
                    'FindSpotsAudit',
                    $rowid,
                    $r['recordID']
                    );
            echo 'You did it!';	
	}
    }
	
    /** Add four figure lat lon for missing ones
     * @access public
     * @return void
     */
    public function fourlatlonAction(){
	ini_set('memory_limit', '512M'); 
	set_time_limit(0);
	$missing = new Findspots();
	$rows = $missing->missingfour(10000);
	foreach($rows as $r){
            $rowid = $r['id'];
            $oldData = $missing->fetchRow('id='  . $rowid)->toArray();
            $where = array();
            $where[] = $missing->getAdapter()->quoteInto('id = ?', $rowid);
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
	
    /** Set elevation where missing
     * @access public
     * @return void
     */
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
            $incorrect->update($updateData, $where);
            $this->_helper->audit($updateData, $oldData, 'FindSpotsAudit', $rowid, $r['recordID']);
            echo $r['recordID'] . '<br />';	
        }
    }
}