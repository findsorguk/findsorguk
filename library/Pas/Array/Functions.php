<?php
class Pas_Array {
	
	public function array_cleanup( $array, $keys  ) {
    	if(is_array($keys)){
	    foreach( $array as $key => $value ) {
	    foreach($keys as $match){
		    if($key == $match){
		            unset($array[$key]);
				}
			}
	    }
    } else {
    	throw new Pas_Exception_BadJuJu('You must submit an array of keys to remove');
    }
	}
	
	public function combine_array($array1,$array2) {
            return array_combine($array1,$array2);
    }
	
}
