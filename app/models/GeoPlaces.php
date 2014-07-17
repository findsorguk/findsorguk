<?php
/**
 * Table for manipluatintg geodata from Yahoo!
 *
 * An example of code use:
 * <code>
 * $adjacent = new GeoPlaces();
 * $adjacent->getAdjacent($woeid);
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
  * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /library/Pas/View/Helper/YahooGeoAdjacent.php
 */
class GeoPlaces extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'geoplanetplaces';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'WOE_ID';

    /** Retrieval of adjacent places in the Yahoo geoplanet dataset
     * @access public
     * @param integer $woeid
     * @return array $data
     * @todo add caching
     */
    public function getAdjacent($woeid) {
        $adj = $this->getAdapter();
        $select = $adj->select()
                ->from($this->_name, array())
                ->joinLeft('geoplanetadjacent',$this->_name
                        . '.WOE_ID = geoplanetadjacent.PLACE_WOE_ID',  array())
                ->joinLeft(array('geos' => $this->_name),
                        'geos.WOE_ID = geoplanetadjacent.NEIGHBOUR_WOE_ID',
                        array('Name', 'WOE_ID'))
                ->where($this->_name . '.WOE_ID = ?', (int)$woeid);
        return $adj->fetchAll($select);
    }
}