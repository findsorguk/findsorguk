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
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example path description
 */
class Pas_ArrayFunctions {

    /** Clean array from getAllParams method
     * @@access public
     * @param array $array
     * @param array $extras
     * @return array
     */
    public function array_cleanup( array $array, array $extras = array() ) {
        $todelete = array(
            'submit', 'action', 'controller',
            'module', 'csrf', 'page');
        if(!empty($extras)) {
            $todelete = array_merge($todelete, $extras);
        }
        foreach( $array as $key => $value ) {
            foreach($todelete as $match){
                if($key == $match){
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }
    
    /** Get the unique key in a multi-dimensional array
     * @access public
     * @param array $array The array to search
     * @param string $sub_key The sub key to search for
     * @return array
     */
    public function unique_multi_array($array, $sub_key) {
        $target = array();
        $existing_sub_key_values = array();
        foreach ($array as $key=>$sub_array) {
            if (!in_array($sub_array[$sub_key], $existing_sub_key_values)) {
                $existing_sub_key_values[] = $sub_array[$sub_key];
                $target[$key] = $sub_array;
            }
        }
        return $target;
    }
    
    /** Function to combine an array
     * @access public
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public function combine(array $array1, array $array2) {
            return array_combine($array1,$array2);
    }

    /** Sort an array by an array
     * @access public
     * @param array $toSort
     * @param array $sortByValuesAsKeys
     * @return array
     */
    public function sortArrayByArray(array $toSort, array $sortByValuesAsKeys){
        $commonKeysInOrder = array_intersect_key(array_flip($sortByValuesAsKeys), $toSort);
        $commonKeysWithValue = array_intersect_key($toSort, $commonKeysInOrder);
        $sorted = array_merge($commonKeysInOrder, $commonKeysWithValue);
        return $sorted;
    }
}