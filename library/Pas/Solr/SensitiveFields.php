<?php

/* 
 * A class to remove sensitive fields from search results as and when needed.
 */

/**
 * Description of SensitiveFields
 *
 * @author danielpett
 */
class Pas_Solr_SensitiveFields {

	/** 
	 * The array of roles that can view the full data set.
	 * @var array
	 */
    protected $_allowed = array('fa','flos','admin','treasure', 'research', 'hero');

    /** 
     * The array of people who can see the personal data set.
     * @var array
     */
    protected $_personal = array('fa','flos','admin','treasure');

	/** 
	 * Check if the data needs cleaning
	 * @param array $data
	 * @param string $role
	 * @param string $core
	 */    
    public function cleanData( $data, $role, $core ){
    if(!in_array($role, $this->_allowed) && $core == 'beowulf'){
       return $this->_processGeoData($data, $role);
    } else {
        return $data;
    }
    }

	/**
	 * Process the data for geo sensitive fields
	 * @param array $data
	 * @param string $role
	 */    
    protected function _processGeoData($data, $role){
        if(is_array($data)){
		$clean = array();
            foreach($data as $record){
            	//If the knownas key exists and is filled in, then it needs restricting
                if(array_key_exists('knownas', $record) && !is_null($record['knownas'])){
                // State that the grids are restricted.
                    $record['parish'] = 'Restricted Access';
                    $record['fourFigure'] = 'Restricted Access';
				// Unset the fourfigure lat/lon
                    unset($record['fourFigureLat']);
                    unset($record['fourFigureLon']);
                } else if(array_key_exists('gridref', $record) && !array_key_exists('knownas', $record)) {
                //Convert the fourfigure grid to required data
                    
                    $record['gridref'] =  $record['fourFigure'];
                    $record['fourFigure'] = $record['fourFigure'];
                }
                //Remove the finder key
                if(array_key_exists('finder', $record) && !in_array($role, $this->_personal)){
                unset($record['finder']);
            }
            $clean[] = $record;
            }
        }
        return $clean;
    }

}


