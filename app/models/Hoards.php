<?php

/** Access, manipulate and delete hoards data.
 *
 * An example of use:
 *
 * <code>
 * <?php
 *
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 4 August 2014
 * @example /app/modules/database/controllers/HoardsController.php
 */
class Hoards extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'hoards';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin','flos','fa','hero','treasure');

    /** The parish stop access
     * @access public
     * @var array
     */
    protected $_parishStop = array('admin','flos','fa','hero','treasure','research');

    /** The restricted access array
     * @access protected
     * @var array
     */
    protected $_restricted = array(null, 'public','member','research');

    /** The error code thrown by the MySQL database when attempting to enter
     * a duplicate value into a unique field
     *
     */
    const DUPLICATE_UNIQUE_VALUE_ERROR_CODE = 23000;


    /**  Get workflow status of a hoard record
     * @access public
     * @param integer $wfStageID
     * @return array
     */
    public function getWorkflowstatus($wfStageID) {
        $select = $this->select()
            ->from($this->_name, array('secwfstage'))
            ->joinLeft('workflowstages',
                'hoards.secwfstage = workflowstages.id',
                array('workflowstage'))
            ->where('hoards.id = ?', (int)$wfStageID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get descriptive, temporal, discovery and recording data of a hoard record
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getHoardData($hoardId){
        $select = $this->select()
            ->from($this->_name, array(
                'id',
                'hoardID',
                'uniqueID' => 'secuid',
                'period1',
                'subperiod1',
                'period2',
                'subperiod2',
                'numdate1',
                'numdate2',
                'broadperiod',
                'lastrulerID',
                'reeceID',
                'terminalyear1',
                'terminalyear2',
                'terminalreason',
                'description',
                'notes',
                'secwfstage',
                'findofnote',
                'findofnotereason',
                'treasure',
                'treasureID',
                'qualityrating',
                'materials',
                'recorderID',
                'identifier1ID',
                'identifier2ID',
                'finderID',
                'finder2ID',
                'disccircum',
                'discmethod',
                'datefound1',
                'datefound2',
                'rally',
                'rallyID',
                'legacyID',
                'other_ref',
                'smrrefno',
                'museumAccession' => 'musaccno',
                'curr_loc',
                'subsequentAction' => 'subs_action',
                'created',
                'createdBy',
                'updated',
                'updatedBy',
                'institution'
            ))
            ->where('hoards.id = ?', (int)$hoardId)
            ->group('hoards.id')
            ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get coin summary for a hoard record
     * @access public
     * @param integer $hoardId
     * @param string $role
     * @return array
     */
    public function getCoinSummary($hoardId){
    // Loop through coinsummary table rows for this hoardID
    }

    /** Get the materials a hoard is made of
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getMaterials($hoardId){
    // Use unserialize to create array suitable for multi-selection box
    // Think about whether this needs to be a separate function
    }

    /** Get summary details of any coins record linked to a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getLinkedCoins($hoardId){
    // These are COIN artefacts from the finds table
    }

    /** Get summary details of any linked artefacts
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getLinkedArtefacts($hoardId){
    // These are artefact records from the finds table
    }

    /** Get summary details of any linked containers
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getLinkedContainers($hoardId){
    // These are artefact records from the finds table with the container checkbox checked
    }

    /** Get the finders of the hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getFinders($hoardId){
    // Use hoardsxfinders table to implement multiple finders functionality
    }

    /** Get images attached to a hoard record
     * @access public
     * @param integer $id
     * @return array
     */
    // Needs modification for Hoards
    public function getImageToFind($id) {
        $key = md5('findtoimage' . $id);
        if (!$data = $this->_cache->load($key)) {

            $select = $this->select()
                ->from($this->_name, array(
                    'old_findID','broadperiod','objecttype'
                ))
                ->joinLeft('users','users.id = finds.createdBy',
                    array('imagedir'))
                ->joinLeft('finds_images',
                    'finds.secuid = finds_images.find_id',
                    array())
                ->joinLeft('slides',
                    'slides.secuid = finds_images.image_id',
                    array('i' => 'imageID','f' => 'filename'))
                ->where('finds.id= ?',(int)$id)
                ->order('slides.imageID ASC')
                ->limit(1);
            $select->setIntegrityCheck(false);
            $data =  $this->getAdapter()->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get findID and secuid for linking images
     * @access public
     * @param string $q
     * @return array
     */
    // Needs modification for Hoards
    public function getImageLinkData($q) {
        $select = $this->select()
            ->from($this->_name, array('term' => 'old_findID','id' => 'secuid'))
            ->where('old_findID LIKE ?', (string)$q . '%')
            ->limit(10);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get the last hoard record created by a specific user
     * @access public
     * @param integer $userId
     * @return array
     */
    // Needs modification for Hoards
    public function getLastRecord($userId) {
        $fieldList = new CopyFind();
        $fields = $fieldList->getConfig();
        $select = $this->select()
            ->from($this->_name,$fields)
            ->joinLeft(array('finderOne' => 'people'),
                'finderOne.secuid = finds.finderID',
                array('finder' => 'fullname'))
            ->joinLeft(array('finderTwo' => 'people'),
                'finderTwo.secuid = finds.finder2ID',
                array('secondfinder' => 'fullname'))
            ->joinLeft(array('identifier' => 'people'),
                'identifier.secuid = finds.identifier1ID',
                array('idby' => 'fullname'))
            ->joinLeft(array('identifierTwo' => 'people'),
                'identifierTwo.secuid = finds.identifier2ID',
                array('id2by' => 'fullname'))
            ->joinLeft(array('recorder' => 'people'),
                'recorder.secuid = finds.finderID',
                array('recordername' => 'fullname'))
            ->where('finds.createdBy = ?', (int)$userId)
            ->order('finds.id DESC')
            ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

}