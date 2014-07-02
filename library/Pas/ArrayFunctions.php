<?php
/** A collection of array functions
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $cleaner = new Pas_ArrayFunctions();
 * $cleanArray = $cleaner->array_cleanup($array);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://URL name
 * @example path description
 */
class Pas_ArrayFunctions {

    /** Clean array from getAllParams method
     * @@access public
     * @param array $array
     * @return array
     */
    public function array_cleanup( array $array ) {
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