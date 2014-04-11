<?php
/**
* Data model for accessing and manipulating scheduled monument data, derived from
* the English Heritage NMR data dump.
* @category 	Pas
* @package 		Pas_Db_Table
* @subpackage 	Abstract
* @author 		Daniel Pett dpett @ britishmuseum.org
* @copyright 	2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		20 August 2010, 12:23:46
* @todo 		add edit and delete functions
* @todo 		make pi a constant
*/

class ScheduledMonuments extends Pas_Db_Table_Abstract {

	protected $_name = 'scheduledMonuments';

	protected $_primaryKey = 'id';



	/** Curl function
	* @param string $url the url to curl
	* @return object
	* @todo replace with Zend_Http class
	*/
	public function get($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
	}

	/** Find SMRs within a certain distance of a lat lon pair, this is set up to work
	* in kilometres from point. You can adapt this for miles. This perhaps can be
	* swapped out for a SOLR based search in future.
	* @param double $lat Latitude
	* @param double $long Longitude
	* @param double $distance distance from point
	* @return array
	*/
	public function getSMRSNearby($lat,$long,$distance = 0.25) {
		$pi = '3.141592653589793';
		$nearbys = $this->getAdapter();
		$select = $nearbys->select()
			->from($this->_name,array( 'monumentName', 'id', 'lat',
			'lon','distance' => 'acos((SIN(' . $pi . '*' . $lat . '/180 ) * SIN(' . $pi . '* lat /180)) + (cos('
			. $pi . '*' . $lat . '/180) * COS(' . $pi .'* lat/180) * COS(' . $pi
			. '* lon/180 - ' . $pi . '* (' . $long . ') /180))) *6378.137'))
			->where('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180) * SIN('
			. $pi . '* lat/180)) + (COS(' . $pi . '*' . $lat
			. '/180) * cos(' . $pi . '* lat /180 ) * COS(' . $pi . '* lon /180 -'
			. $pi . '* ( ' . $long . ')/180))) <=' . $distance)
			->where('1=1')
			->order('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180 ) * SIN('
			. $pi . '* lat/180)) + (COS(' . $pi . '*' . $lat . '/180) * cos('
			. $pi . ' * lat /180 ) * COS(' . $pi . '* lon /180 - '
			. $pi . '*  (' . $long . ' )/180))) ASC');
	return $nearbys->fetchAll($select);
	}

	/** Find objects recorded with proximity to SMRs within a certain distance of a lat lon pair,
	* this is set up to work in kilometres from point. You can adapt this for miles. This perhaps can be
	* swapped out for a SOLR based search in future.
	* @param double $lat Latitude
	* @param double $long Longitude
	* @param double $distance distance from point
	* @return array
	*/
	public function getSMRSNearbyFinds($lat,$long,$distance) {
		$pi = '3.141592653589793';
		$nearbys = $this->getAdapter();
		$select = $nearbys->select()
			->from('finds',array('old_findID','id','objecttype'))
			->joinLeft('findspots','finds.secuid = findspots.findID', array( 'county', 'declat', 'declong',
			'distance' => 'acos((SIN(' . $pi . '*' . $lat . '/180 ) * SIN(' . $pi . '* declat /180)) + (cos('
			. $pi . '*' . $lat . '/180) * COS(' . $pi . '* declat/180) * COS(' . $pi
			. '* declong/180 - ' . $pi . '* (' . $long . ') /180))) *6378.137'))
			->where('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180) * SIN(' . $pi
			. '* declat/180)) + (COS(' . $pi . '*' . $lat . '/180) * cos('
			. $pi . '* declat /180 ) * COS(' . $pi . '* declong /180 -' . $pi
			. '* ( ' . $long . ')/180))) <=' . $distance)
			->where('1=1')
			->order('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180 ) * SIN(' . $pi
			. '* declat/180)) + (COS(' . $pi . '*' . $lat . '/180) * cos('
			. $pi . ' * declat /180 ) * COS(' . $pi . '* declong /180 - '
			. $pi . '*  (' . $long . ' )/180))) ASC');
		return $nearbys->fetchAll($select);
	}

	/**
	* Get a paginated list of Scheduled monuments
	* @param integer $page the page number
	* @param string $county assigned county
	* @param string $district assigned district
	* @param string $parish assigned parish
	* @param string $monumentName
	* @return array
	*/
	public function getSmrs($page,$county = NULL,$district = NULL,$parish = NULL,$monumentName = NULL) {
			$nearbys = $this->getAdapter();
			$select = $nearbys->select()
				->from($this->_name)
				->order('county');
			if(isset($monumentName) && ($monumentName != "")){
			$select->where('monumentName LIKE ?',(string)'%' . $monumentName . '%');
			}
			if(isset($district) && ($district != "")){
			$select->where('district = ?',(string)$district);
			}
			if(isset($county) && ($county != "")){
			$select->where('county = ?',(string)$county);
			}
			if(isset($parish) && ($parish != "")){
			$select->where('parish = ?',(string)$parish);
			}
			$paginator = Zend_Paginator::factory($select);
			$paginator->setItemCountPerPage(20)
				->setPageRange(10);
			if(isset($page) && ($page != "")) {
		    $paginator->setCurrentPageNumber((int)$page);
		}
		return $paginator;
	}

	/**
	* Get a paginated list of Scheduled monuments by Yahoo WOEID
	* @param integer $page the page number
	* @param integer $id the WOEID
	* @return array
	*/
	public function getSmrsByWoeid($id,$page){
			$nearbys = $this->getAdapter();
			$select = $nearbys->select()
				->from($this->_name)
				->order('county')
				->where('woeid = ?',(int)$id);
			$paginator = Zend_Paginator::factory($select);
			$paginator->setCache($this->_cache);
			$paginator->setItemCountPerPage(20)
			          ->setPageRange(10);
			if(isset($page) && ($page != "")) {
		    $paginator->setCurrentPageNumber($page);
			}
		return $paginator;
	}

	/** Get a Scheduled monument by id number
	* @param integer $id the id of monument
	* @return array
	* @todo change to fetchrow?
	*/
	public function getSmrDetails($id) {
			$nearbys = $this->getAdapter();
			$select = $nearbys->select()
				->from($this->_name)
				->where('id = ?',(int)$id);
		return $nearbys->fetchAll($select);
	}

	/** Get a list of Scheduled monument as key value pairs
	* @return array
	*/
	public function listMonuments() {
			$select = $this->select()
				->from($this->_name, array('id','monumentName'))
				->order('monumentName');
			$options = $this->getAdapter()->fetchPairs($select);
		return $options;
    }

	/** Get a list of Scheduled monuments within a constituency
	* @param $constituency The constituency to query
	* @return array
	* @todo change over to YQL query
	* @todo add cache
	*/
	public function getSmrsConstituency($constituency) {
			$twfy = 'http://www.theyworkforyou.com/api/getGeometry?name='
			. urlencode($constituency) . '&output=js&key=CzhqDaDMAgkMEcjdvuGZeRtR';
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
				->where('lat > ?',(double)$latmin)
				->where('lat < ?',(double)$latmax)
				->where('lon > ?',(double)$longmin)
				->where('lon < ?',(double)$longmax);
			$osdata = $finds->fetchAll($select);
		return  $osdata;
		} else {
			return NULL;
		}
	}

	/** Get a list of Scheduled monuments by a query string
	* @param $q The query string of monument
	* @return array
	* @todo change method to SOLR?
	*/
	public function nameLookup($q){
			$mons = $this->getAdapter();
			$select = $mons->select()
				->from($this->_name, array('id' => 'monumentName','term' => 'monumentName'))
				->where('monumentName LIKE ?', (string)'%' . $q . '%')
				->order('monumentName')
				->limit(10);
	return $mons->fetchAll($select);
	}

	/** Get a list of Scheduled monuments by a query string
	* @param $q The query string of monument
	* @return array
	* @todo change method to SOLR?
	*/
	public function samLookup($q){
		$mons = $this->getAdapter();
		$select = $mons->select()
            ->from($this->_name, array('id','term' => 'monumentName'))
			->where('monumentName LIKE ?', '%' . $q . '%')
			->order('monumentName')
			->limit(10);
	   return $mons->fetchAll($select);
	}

}