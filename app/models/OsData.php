<?php
/** Model for interacting with OS data from their opendata downloads
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new OsData();
 * $data = $model->getGazetteer($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/models/ScheduledMonuments.php
 *
 */

class OsData extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'osdata';

    /** The primary key
     * @access protected
     * @var type
     */
    protected $_primary = 'id';

    /** Get all adjacent OSData points in set radius
     * @param double $lat
     * @param double $long
     * @param integer $distance
     * @return array
     */
    public function getSMRSNearby($lat, $long, $distance = 1) {
        $pi = '3.141592653589793';
        $nearbys = $this->getAdapter();
        $select = $nearbys->select()
                ->from($this->_name,array(
                    'name', 'id', 'latitude',
                    'longitude', 'distance' => 'acos((SIN(' . $pi . '*' . $lat
                        . '/180 ) * SIN('
                    . $pi . '* latitude /180)) + (cos(' . $pi . '*' . $lat
                        . '/180) * COS(' . $pi
                        . '* latitude/180) * COS(' . $pi . '* longitude/180 - '
                    . $pi . '* ('
                    . $long . ') /180))) *6378.137'))
                ->where('6378.137 * ACOS((SIN(' . $pi . '*' . $lat . '/180) * SIN('
                        . $pi .'* latitude/180)) + (COS(' . $pi . '*' . $lat
                        . '/180) * cos(' . $pi
                        . '* latitude /180 ) * COS(' . $pi . '* longitude /180 -'
                        . $pi . '* ( '
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
        $key = md5('gaz' . (int)$id);
        if (!$data = $this->_cache->load($key)) {
            $gazetteers = $this->getAdapter();
            $select = $gazetteers->select()
                    ->from($this->_name)
                    ->where('id = ?', (int)$id);
            $data = $gazetteers->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get OS feature data from 1:50K gazetteer
     * @access public
     * @param type $constituency
     * @return null|array
     */
    public function getGazetteerConstituency($constituency) {
        $twfy = 'http://www.theyworkforyou.com/api/getGeometry?name=';
        $twfy .= urlencode($constituency);
        $twfy .= '&output=js&key=';
        $twfy .= $this->_config->webservice->twfy->apikey;
        $curl = new Pas_Curl();
        $curl->setUri($twfy);
        $curl->getRequest();
        $data = $curl->getJson();
        if(array_key_exists('min_lat',$data)) {
            $latmin = $data->min_lat;
            $latmax = $data->max_lat;
            $longmin = $data->min_lon;
            $longmax = $data->max_lon;

            $finds = $this->getAdapter();

            $select = $finds->select()
                    ->from($this->_name)
                    ->where('latitude > ?', $latmin)
                    ->where('latitude < ?', $latmax)
                    ->where('longitude > ?', $longmin)
                    ->where('longitude < ?', $longmax)
                    ->where(new Zend_Db_Expr('f_code = "R" OR f_code = "A"'));
            $osdata = $finds->fetchAll($select);
            return  $osdata;
        } else {
            return NULL;
        }
    }

    /** Get paginated gazetteer list of osdata
     * @access public
     * @param integer $page
     * @param string $county
     * @param string $district
     * @param string $parish
     * @param string $monumentName
     * @return \Zend_Paginator
     */
    public function getSmrs($page, $county, $district, $parish, $monumentName) {
        $acros = $this->getAdapter();
        $select = $acros->select()
                ->from($this->_name,array(
                    'county' => 'full_county', 'gridref' => 'km_ref',
                    'monumentName' => 'name',
                    'id', 'f_code'
                    ))
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
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(20)->setPageRange(10);
        if(isset($page) && ($page != "")) {
            $paginator->setCurrentPageNumber($page);
        }
        return $paginator;
    }
}