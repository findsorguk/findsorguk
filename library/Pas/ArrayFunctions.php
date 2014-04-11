<?php
class Pas_ArrayFunctions {
	
	public function array_cleanup( $array ) {
        $todelete = array('submit','action','controller','module','csrf');
	foreach( $array as $key => $value ) {
        foreach($todelete as $match){
    	if($key == $match){
    		unset($array[$key]);
    	}
        }
        }
        return $array;
        }
	
}