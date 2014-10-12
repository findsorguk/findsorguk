<?php
/**
 * Model for accessing multiple finders linked to hoards
 *
 * @author Mary Chester-Kadwell <mchester-kadwell@britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 7 September 2014
*/
class HoardsFinders extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'hoards_finders';

    /** Add new finders of a hoard
     * @param array
     * @return int
     */
    public function addFinders($findersData){
        $insertData['hoardID'] = $findersData['hoardID'];
        foreach ($findersData['finderID'] as $id) {
            if($id != NULL) {
                $insertData['finderID'] = $id;
                $insertFinder = $this->add($insertData);
            }
        }
        // Returns the last id produced by the loop
        return $insertFinder;
    }
}
