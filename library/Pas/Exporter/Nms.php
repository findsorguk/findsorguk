<?php
ini_set('memory_limit', '64M');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Nms
 * This class is just for the use of Norfolk. Complete waste of time
 * implementing it, every one else can use csv and adapt it to their own needs.
 * @category Pas
 * @package Pas_Exporter
 * @version 1
 * @since 1/4/12
 * @license GNU
 * @copyright Daniel Pett
 * @author Daniel Pett
 */
class Pas_Exporter_Nms extends Pas_Exporter_Generate {

    protected $_format = 'nms';

    /** The fields to return
     *
     * @var array
     */
    protected $_nmsFields = array(
		'id','old_findID','description',
        'fourFigure','gridref', 'county',
		'district', 'parish','knownas',
        'finder', 'smrRef','otherRef',
        'identifier', 'objecttype', 'broadperiod'
        );

    public function __construct() {
        parent::__construct();
    }

    /** Create the data for export
     * @access public
     * @return array
     */
    public function create(){
    $params = array_merge($this->_params, array('show' => 500, 'format' => 'pdf'));
    $this->_search->setFields($this->_nmsFields);
    $this->_search->setParams($params);
    $this->_search->execute();
//    $this->_search->debugQuery();
    return  $this->_clean($this->_search->_processResults());
    }

    protected function _clean($data){
    	$finalData = array();
        foreach($data as $dat){
		$record = array();
        foreach($dat as $k => $v){
            $record[$k] = trim(strip_tags(str_replace('<br />',array( ""),
                    utf8_decode( $v ))));
        }
        $finalData[] = $record;
        }
    return $finalData;
    }

}

