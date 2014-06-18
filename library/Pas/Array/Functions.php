<?php
/**
 * A class with various array functions
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @category Pas
 * @package Array
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 *
 */

class Pas_Array {

    /** Clean up array
     * @access public
     * @param array $array
     * @param array$keys
     * @throws Pas_Exception
     * @return array Cleaned up array
     */
    public function array_cleanup( array $array, array $keys  ) {
        if(is_array($keys)){
            foreach( $array as $key => $value ) {
                foreach($keys as $match){
                    if($key == $match){
                        unset($array[$key]);
                        }
                    }
                }
             } else {
                 throw new Pas_Exception('You must submit an array of keys to remove', 500);
            }
    }

    /** Combine two arrays
     * @access public
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public function combine_array(array $array1, array $array2) {
        return array_combine($array1,$array2);
    }

}
