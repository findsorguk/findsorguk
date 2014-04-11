<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kml
 *
 * @author danielpett
 */
class Pas_Exporter_Kml extends Pas_Exporter_Generate {

    protected $_format = 'kml';


    protected $_kmlFields = array(
	'id','old_findID','description', 'fourFigure',
        'longitude', 'latitude', 'county', 'woeid',
	'district', 'parish','knownas', 'thumbnail'
        );

    protected $_remove = array('public','member',null);


    public function __construct() {
        parent::__construct();
    }

    public function create(){
    $this->_search->setFields($this->_kmlFields);
    $this->_search->setParams($this->_params);
    $this->_search->execute();
    return  $this->_clean($this->_search->_processResults());
    }

    protected function _clean($data){
        foreach($data as $dat){

        if(in_array($this->_role,$this->_remove)){
            $grids = new Pas_Geo_Gridcalc($dat['fourFigure']);
            $geo = $grids->convert();
            $dat['latitude'] = $geo['decimalLatLon']['decimalLatitude'];
            $dat['longitude'] = $geo['decimalLatLon']['decimalLongitude'];
        }
        foreach($dat as $k => $v){
            $record[$k] = trim(strip_tags(str_replace('<br />',array( "\n", "\r"), utf8_decode( $v ))));
        }
        $finalData[] = $record;
        }
    return $finalData;
    }

}




