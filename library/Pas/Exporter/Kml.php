<?php
/** An extension of the base generator class to export data as KML.
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Exporter
 * @version 1
 * @license http://URL name
 * 
 */
class Pas_Exporter_Kml extends Pas_Exporter_Generate {

    /** The format name
     * @access protected
     * @var string
     */
    protected $_format = 'kml';
    
    /** The default kml fields
     * @access protected
     * @var array
     */
    protected $_kmlFields = array(
	'id', 'old_findID', 'description', 
        'fourFigure', 'longitude', 'latitude', 
        'county', 'woeid', 'district', 
        'parish', 'knownas', 'thumbnail'
        );

    /** The array of roles where we need to remove data
     * @access protected
     * @var array
     */
    protected $_remove = array('public','member',null);

    /** Constructor 
     * This uses the parent class
     */
    public function __construct() {
        parent::__construct();
    }

    /** Get the results
     * @access public
     * @return array
     */
    public function create(){
        $this->_search->setFields($this->_kmlFields);
        $this->_search->setParams($this->_params);
        $this->_search->execute();
        return  $this->_clean($this->_search->_processResults());
    }

    /** Clean the results
     * @access protected
     * @param array $data
     * @return array
     */
    protected function _clean( array $data){
        foreach($data as $dat){

            if(in_array($this->_role,$this->_remove)){
                $grids = new Pas_Geo_Gridcalc($dat['fourFigure']);
                $geo = $grids->convert();
                $dat['latitude'] = $geo['decimalLatLon']['decimalLatitude'];
                $dat['longitude'] = $geo['decimalLatLon']['decimalLongitude'];
            }
            foreach($dat as $k => $v){
                $record[$k] = trim(strip_tags(str_replace('<br />',
                        array( "\n", "\r"), utf8_decode( $v ))));
            }
            $finalData[] = $record;
        }
        return $finalData;
    }
}