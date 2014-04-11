<?php
/**Model for interacting with OS data from their opendata downloads
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
*/

class Osdata
	extends Pas_Db_Table_Abstract {

	protected $_name = 'osdata';

	protected $_primary = 'id';

	/** Get all adjacent OSData points in set radius
	* @param double $lat
	* @param double $lon
	* @param integer $distance
	* @return array
	* @todo set pi as a constant?
	* @todo change function name to correct version
	*/
	public function getSMRSNearby($lat,$long,$distance = 1) {
        $pi = '3.141592653589793';
        $nearbys = $this->getAdapter();
        $select = $nearbys->select()
                ->from($this->_name,array('name', 'id', 'latitude',
                'longitude', 'distance' => 'acos((SIN(' . $pi . '*' . $lat . '/180 ) * SIN('
                . $pi . '* latitude /180)) + (cos(' . $pi . '*' . $lat . '/180) * COS(' . $pi
                . '* latitude/180) * COS(' . $pi . '* longitude/180 - ' . $pi . '* ('
                . $long . ') /180))) *6378.137'))
                ->where('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180) * SIN('
                . $pi .'* latitude/180)) + (COS(' . $pi . '*' . $lat . '/180) * cos(' . $pi
                . '* latitude /180 ) * COS(' . $pi . '* longitude /180 -' . $pi . '* ( '
                . $long . ')/180))) <=' . $distance)
                ->where('1 = 1')
                ->where(new Zend_Db_Expr('f_code = "R" OR f_code = "A"'))
            ->order('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180 ) * SIN(' . $pi
            . '* latitude/180)) + (COS(' . $pi . '*' . $lat . '/180) * cos('
            . $pi . ' * latitude /180 ) * COS(' . $pi . '* longitude /180 - ' . $pi
            . '*  (' . $long . ' )/180))) ASC');
     	return $nearbys->fetchAll($select);
		}

	/** Get information for a gazetteer id
	* @param integer $id
	* @return array
	*/
	public function getGazetteer($id) {
		if (!$data = $this->_cache->load('gaz' . (int)$id)) {
		$gazetteers = $this->getAdapter();
		$select = $gazetteers->select()
			->from($this->_name)
			->where('id = ?', (int)$id);
        $data = $gazetteers->fetchAll($select);
		$this->_cache->save($data, 'gaz' . (int)$id);
		}
        return $data;
	}

	 /** Perform curl operation
	* @return object
	* @todo change this to zend_http method
	*/
	public function get($url){
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  $output = curl_exec($ch);
	  curl_close($ch);
	  return $output;
	}

	/** Get OS feature data from 1:50K gazetteer
	* @param string $constituency
	* @return Array
	* @todo switch this to YQL method?
	*/
	public function getGazetteerConstituency($constituency) {
	$twfy = 'http://www.theyworkforyou.com/api/getGeometry?name='.urlencode($constituency).'&output=js&key=CzhqDaDMAgkMEcjdvuGZeRtR';
	$data = $this->get($twfy);
	$data = json_decode($data);
	if(array_key_exists('min_lat',$data)) {
	$latmin = $data->min_lat;
	$latmax = $data->max_lat;
	$longmin = $data->min_lon;
	$longmax = $data->max_lon;

	$finds = $this->getAdapter();

	$select = $finds->select()
		->from($this->_name)
		->where('latitude > ?',$latmin)
		->where('latitude < ?',$latmax)
		->where('longitude > ?',$longmin)
		->where('longitude < ?',$longmax)
		->where(new Zend_Db_Expr('f_code = "R" OR f_code = "A"'));
		$osdata = $finds->fetchAll($select);
	return  $osdata;
	} else {
	return NULL;
	}
	}

	/** Get paginated gazetteer list of osdata
	* @param string $monumentName
 	* @param string $district
 	* @param string $county
 	* @param string $parish
 	* @param integer $page
	* @return Array
	* @todo change function name, copied from SMR class
	*/
	public function getSmrs($page,$county = NULL,$district = NULL,$parish = NULL,$monumentName = NULL) {
	$acros = $this->getAdapter();
	$select = $acros->select()
			->from($this->_name,array('county' => 'full_county', 'gridref' => 'km_ref', 'monumentName' => 'name',
			'id', 'f_code'))
			->where(new Zend_Db_Expr('f_code = "R" OR f_code = "A"'))
			->order('county');
	if(isset($monumentName) && ($monumentName != "")){
	$select->where('monumentName LIKE ?', (string)'%' . $monumentName . '%');
	}
	if(isset($district) && ($district != "")){
	$select->where('district = ?', (string)$district);
	}
	if(isset($county) && ($county != "")){
	$select->where('county = ?', (string)$county);
	}
	if(isset($parish) && ($parish != "")){
	$select->where('parish = ?', (string)$parish);
	}
	$data = $acros->fetchAll($select);
	$paginator = Zend_Paginator::factory($data);
	$paginator->setCache($this->_cache);
	$paginator->setItemCountPerPage(20)
	          ->setPageRange(10);
	if(isset($page) && ($page != ""))
	{
    $paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}
}