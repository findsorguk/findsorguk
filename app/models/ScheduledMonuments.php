<?php
/**
 * Data model for accessing and manipulating scheduled monument data, derived 
 * from the English Heritage NMR data dump.
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $smrs = new ScheduledMonuments();
 * $this->view->smrs = $smrs->getSmrDetails($this->_getParam('id'));
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 20 August 2010, 12:23:46
 * @example /app/modules/datalabs/controllers/SmrController.php
*/
class ScheduledMonuments extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'scheduledMonuments';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';

    /** Find SMRs within a certain distance of a lat lon pair, this is set up to work
     * in kilometres from point. You can adapt this for miles. This perhaps can be
     * swapped out for a SOLR based search in future.
     * @access public
     * @param double $lat
     * @param double $long
     * @param integer $distance
     * @return array
     */
    public function getSMRSNearby($lat,$long,$distance = 0.25) {
        $pi = '3.141592653589793';
        $nearbys = $this->getAdapter();
        $select = $nearbys->select()
                ->from($this->_name,array( 
                    'monumentName', 'id', 'lat',
                    'lon','distance' => 'acos((SIN(' . $pi . '*' . $lat 
                        . '/180 ) * SIN(' . $pi . '* lat /180)) + (cos('
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

    /** Find objects recorded with proximity to SMRs within a certain distance 
     * of a lat lon pair, this is set up to work in kilometres from point. You 
     * can adapt this for miles. This perhaps can be swapped out for a SOLR 
     * based search in future.
     * @access public
     * @param double $lat
     * @param double $long
     * @param integer $distance
     * @return array
     */
    public function getSMRSNearbyFinds($lat, $long, $distance) {
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

    /** Get a paginated list of Scheduled monuments
     * @access public
     * @param integer $page
     * @param  string $county
     * @param string $district
     * @param string $parish
     * @param string $monumentName
     * @return type
     */
    public function getSmrs($page, $county, $district, $parish, $monumentName) {
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

    /** Get a paginated list of Scheduled monuments by Yahoo WOEID
     * @access public
     * @param integer $id
     * @param integer $page
     * @return \Zend_Paginator
     */
    public function getSmrsByWoeid($id,$page){
        $nearbys = $this->getAdapter();
        $select = $nearbys->select()
                ->from($this->_name)
                ->order('county')
                ->where('woeid = ?',(int)$id);
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber((int)$page);
        }
        return $paginator;
    }

    /** Get a Scheduled monument by id number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSmrDetails($id) {
        $nearbys = $this->getAdapter();
        $select = $nearbys->select()
                ->from($this->_name)
                ->where('id = ?',(int)$id);
        return $nearbys->fetchAll($select);
    }

    /** Get a list of Scheduled monument as key value pairs
     * @access public
     * @return array
     */
    public function listMonuments() {
        $select = $this->select()
                ->from($this->_name, array('id','monumentName'))
                ->order('monumentName');
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get a list of Scheduled monuments within a constituency
     * @access public
     * @param string $constituency
     * @return null
     */
    public function getSmrsConstituency($constituency) {
        $twfy = 'http://www.theyworkforyou.com/api/getGeometry?name=';
        $twfy .= urlencode((string)$constituency);
        $twfy .= '&output=js&key=';
        $twfy .= $this->_config->webservice->twfy->apikey;
        $curl = new Pas_Curl();
        $curl->setUri($twfy);
        $curl->getRequest();
        $data = $curl->decodeJson();
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
            return FALSE;
        }
    }

    /** Get a list of Scheduled monuments by a query string
     * @access public
     * @param string $q
     * @return array
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
     * @access public
     * @param string $q
     * @return array
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