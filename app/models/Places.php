<?php
/** Retrieve and manipulate data from the places listing
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $places = new Places();
 * $toupdate = $missing->getMissingDistrict();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/admin/controllers/GridsController.php
 */
class Places extends Pas_Db_Table_Abstract {
    
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'places';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'ID';


    /** Get the district by county
     * @access public
     * @param string $county
     * @return array
     */
    public function getDistrict($county){
        $districts = $this->getAdapter();
        $select = $districts->select()
                ->from($this->_name, array('id' => 'district','term' => 'district'))
                ->where('county LIKE ?', (string)'%' . $county . '%')
                ->where('district IS NOT NULL')
                ->where('county IS NOT NULL')
                ->order('district')
                ->group('district');
        return $districts->fetchAll($select);
    }

    /** Get the district by county as dropdown list
     * @access public
     * @param string $county
     * @return array
     */
    public function getDistrictList($county) {
        $districts = $this->getAdapter();
        $select = $districts->select()
                ->from($this->_name, array('id' => 'district','term' => 'district'))
                ->where('county = ?', (string)$county)
                ->where('district IS NOT NULL')
                ->where('county IS NOT NULL')
                ->order('district')
                ->group('district');
        return $districts->fetchPairs($select);
    }
    
    /** Get the parish by district
     * @access public
     * @param string $district
     * @return array
     */
    public function getParish($district) {
        $parishes = $this->getAdapter();
        $select = $parishes->select()
                ->from($this->_name, array('id' => 'parish','term' =>'parish'))
                ->where('district = ?', (string)$district)
                ->where('district IS NOT NULL')
                ->where('county IS NOT NULL')
                ->where('parish IS NOT NULL')
                ->order('parish')
                ->group('term');
        return $parishes->fetchAll($select);
    }

    /** Get the parish by district as a dropdown array
     * @access public
     * @param string $district
     * @return array
     */
    public function getParishList($district) {
        $parishes = $this->getAdapter();
        $select = $parishes->select()
                ->from($this->_name, array('id' => 'parish','term' =>'parish'))
                ->where('district = ?', (string)$district)
                ->where('district IS NOT NULL')
                ->where('county IS NOT NULL')
                ->where('parish IS NOT NULL')
                ->order('parish')
                ->group('term');
        return $parishes->fetchPairs($select);
    }

    /** Get the parishes by county
     * @access public
     * @param string $county
     * @return array
     */
    public function getParishByCounty($county) {
        $parishes = $this->getAdapter();
        $select = $parishes->select()
                ->from($this->_name, array('id' => 'parish','term' =>'parish'))
                ->where('county = ?', (string)$county)
                ->where('district IS NOT NULL')
                ->where('county IS NOT NULL')
                ->where('parish IS NOT NULL')
                ->order('parish')
                ->group('parish');
        return $parishes->fetchAll($select);
    }

    /** Get the district by county
     * @access public
     * @param string $county
     * @return array
     */
    public function getDistrictByParish($county) {
        $districts = $this->getAdapter();
        $select = $districts->select()
                ->from($this->_name, array('id' => 'district','term' =>'district'))
                ->where('parish = ?', (string)$county)
                ->where('district IS NOT NULL')
                ->where('county IS NOT NULL')
                ->where('parish IS NOT NULL')
                ->order('district')
                ->group('district');
        return $districts->fetchAll($select);
    }

    /** Get the stuff to update for districts
     * @access public
     * @param string $county
     * @param string $parish
     * @return array
     */
    public function getDistrictUpdate($county, $parish){
        $districts = $this->getAdapter();
        $select = $districts->select()
                ->from($this->_name, array('district'))
                ->where('parish = ?', (string)$parish)
                ->where('county = ?', (string)$county);
       return $districts->fetchAll($select);
    }
}