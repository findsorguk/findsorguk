<?php
/**
 * A model to manipulate data for the Counties of England and Wales. 
 * Scotland may be added in the future 
 * 
 * An example of use:
 * <code>
 * <?php
 * $model = new Counties();
 * $data = $model->getCountyName();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo Probably deprecate this as we now use OS opendata
 * @example /findsorguk/app/forms/ImageEditForm.php
 */

class Counties extends Pas_Db_Table_Abstract {
	
    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'counties';
    
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'ID';

    /** Retrieve a key pair list of counties in England and Wales for dropdown 
     * use
     * @access public
     * @return array
     */
    public function getCountyname() {
        $key = md5('countynames');
	if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('ID', 'county'))
                    ->order('county')
                    ->where('valid = ?', (int)1);
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
	}
	return $data;
    }
	
    /** Retrieve region list for county
     * @access public
     * @param string $county 
     * @return array
     */
    public function getRegions($county) {
        $key = md5('regions' . $county);
        if (!$data = $this->_cache->load($key)) {
            $regions = $this->getAdapter();
            $select = $regions->select()
                    ->from($this->_name, array())
                    ->joinLeft('regions','regions.id = counties.regionID',
                            array('id','term' =>'region'))
                    ->where('county = ?',$county);
            $data =  $regions->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve region list key pair valies for county
     * @access public
     * @param string $county 
     * @return array
     */
    public function getRegionsList($county) {
        $key = md5('regionlist' . $county);
        if (!$data = $this->_cache->load($key)) {
            $regions = $this->getAdapter();
            $select = $regions->select()
                    ->from($this->_name, array())
                    ->joinLeft('regions','regions.id = counties.regionID',
                            array('id','term' =>'region'))
                    ->where('county = ?',$county);
            $data =  $regions->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Retrieve county list again as key pairs. 
     * @access public 
     * @return array
     */
    public function getCountyname2() {
        $key = md5('countynames2');
        if (!$data = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('county', 'county'))
                    ->order('county')
                    ->where('valid = ?', (int)1);
            $data = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}