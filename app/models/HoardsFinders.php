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
    public function getFinders($hoardID){
        $select = $this->select()
            ->from($this->_name, array(
                'id',
                'hoardID',
                'finderID',
                'viewOrder'
            ))
            ->joinLeft('people', 'hoards_finders.finderID = people.secuid',
                array('finder' => 'fullname'))
            ->where('hoardID = ?', $hoardID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Add new finders of a hoard
     * @param array
     * @return int
     */
    public function addFinders($findersData){
        $insertData['hoardID'] = $findersData['hoardID'];
        foreach ($findersData['finderID'] as $finder) {
                    if($finder['finderID'] != NULL) {
                        $insertData['finderID'] = $finder['finderID'];
                        $insertData['viewOrder'] = $finder['viewOrder'];
                        $insertFinder = $this->add($insertData);
            }
        }
        return;
    }

    /** Update finders of a hoard
     * @param array
     * @return int
     */
    public function updateFinders(array $findersData){
        $updateData['hoardID'] = $findersData['hoardID'];
        $where[0] = $this->getAdapter()->quoteInto('hoardID = ?', $updateData['hoardID']);

        foreach ($findersData['finderID'] as $finder) {
            if($finder['finderID'] != NULL) { // If there is at least one finderID
                $updateData['finderID'] = $finder['finderID'];
                $updateData['viewOrder'] = $finder['viewOrder'];
                $where[1] = $this->getAdapter()->quoteInto('viewOrder = ?', $finder['viewOrder']);

                // Check if finder row already exists
                $select = $this->select()
                    ->from($this->_name, array(
                        'hoardID', 'viewOrder'
                    ))
                    ->where('hoardID = ?', $updateData['hoardID'])
                    ->where('viewOrder = ?', $finder['viewOrder']);
                $select->setIntegrityCheck(false);
                $row = $this->getAdapter()->fetchRow($select);
                if($row != false){ // If exists, update
                    $updateFinder = $this->update($updateData, $where);
                } else { // If not exists, add
                    $updateFinder = $this->add($updateData);
                }
            }
        }

        // Delete any old finders
        $numberOfNewFinders = count($findersData['finderID']);
        $select = $this->select()
            ->from($this->_name, array(
                'hoardID', 'viewOrder'
            ))
            ->where('hoardID = ?', $updateData['hoardID']);
        $rowset = $this->getAdapter()->fetchAll($select);
        $numberOfOldFinders = count($rowset);
        if($numberOfNewFinders == 0 && $numberOfOldFinders == 0){ // Do nothing for no changes to zero finders
            return;
        } else {
            for ($i = $numberOfNewFinders + 1; $i <= $numberOfOldFinders; $i++) {
                $where[1] = $this->getAdapter()->quoteInto('viewOrder = ?', $i);
                $deleteFinder = $this->delete($where);
            }
        }
        return;
        }
}
