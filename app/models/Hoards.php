<?php

/** Access, manipulate and delete hoards data.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Hoards();
 * $data = $model->getBasicHoardData($id);
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 4 August 2014
 * @example /app/modules/database/controllers/HoardsController.php
 */
class Hoards extends Pas_Db_Table_Abstract
{

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
    protected $_higherlevel = array('admin', 'flos', 'fa', 'hero', 'treasure');

    /** The parish stop access
     * @access public
     * @var array
     */
    protected $_parishStop = array('admin', 'flos', 'fa', 'hero', 'treasure', 'research');

    /** The restricted access array
     * @access protected
     * @var array
     */
    protected $_restricted = array(null, 'public', 'member', 'research');

    /** The array of numismatic terms
     * @var array coins pseudonyms
     */
    protected $_coinarray = array(
        'Coin', 'COIN', 'coin',
        'token', 'jetton', 'coin weight',
        'COIN HOARD', 'TOKEN', 'JETTON'
    );

    /** The error code thrown by the MySQL database when attempting to enter
     * a duplicate value into a unique field
     *
     */
    const DUPLICATE_UNIQUE_VALUE_ERROR_CODE = 23000;

    /** Generates an old_hoardID for new hoard records
     * @access    public
     * @return    string $hoardId The old_hoardID
     * @throws  Pas_Exception_NotAuthorised
     */
    public function generateHoardId()
    {
        $institution = $this->getInstitution();
        if (!is_null($institution)) {
            list($usec, $sec) = explode(" ", microtime());
            $suffix = strtoupper(substr(dechex($sec), 3) . dechex(round($usec * 15)));
            $hoardId = $institution . '-' . $suffix;
            return $hoardId;
        } else {
            throw new Pas_Exception_Group('Institution missing', 500);
        }
    }

    /** Prepare hoard finders into an array for database insertion
     * @access    public
     * @return    array of arrays $finders
     */
    public function prepareFinders($insertData) {
        // This loop removes non-finder fields
        // And empties finder IDs for empty finder names
        foreach ($insertData as $field => $value) {
            if ((strpos($field, 'finder') === false)) {
                unset($insertData[$field]);
            } elseif (strpos($field, 'ID') === false) {
                if (empty($value)) {
                    $insertData[$field . 'ID'] = "";
                }
                unset($insertData[$field]);
            }
        }
        // Make sure the finders are sorted in ascending order
        ksort($insertData);
        // This loop gives each finder ID a suitable order integer
        // compensating for empty finder IDs
        $finders = array();
        $order = 1;
        foreach ($insertData as $field => $value) {
            if(!empty($value)){
                $finders[] = array('finderID' => $value, 'order' => $order);
                $order += 1;
            }
        }
        return $finders;
    }

    /** Add a new hoard record
     * @param array
     * @return int
     */
    public function addHoard($insertData)
    {
        $insertData['secuid'] = $this->generateSecuId();
        $insertData['hoardID'] = $this->generateHoardId();
        $insertData['secwfstage'] = (int)2;
        $insertData['institution'] = $this->getInstitution();
        unset($insertData['recordername']);
        unset($insertData['idBy']);
        unset($insertData['id2by']);
        unset($insertData['hiddenfield']);
        $insertData['materials'] = serialize($insertData['materials']);

        // Takes the arbitrarily long list of finders and prepares an array suitable
        // for inserting into the Hoards_Finders table
        $findersData['finderID'] = $this->prepareFinders($insertData);
        // Removes the old finder ID values from the data to be inserted into Hoards table
        foreach ($insertData as $field => $value) {
            if (strpos($field, 'finder') !== false) {
                unset($insertData[$field]);
            }
        }
        // Adds the hoard ID to the array of finders for insertion into Hoards_Finders table
        $findersData['hoardID'] = $insertData['secuid'];
        $findersTable = new HoardsFinders();

        $i = 2;
        while ($i > 0) {
            try {
                $insert = $this->add($insertData);
                $insertFinders = $findersTable->addFinders($findersData);
                break;
            } catch (Zend_Db_Exception $e) {
                $code = $e->getCode();
                // If there is a duplicate unique value, generates a new old_findsID and tries again up to twice
                if ($code == self::DUPLICATE_UNIQUE_VALUE_ERROR_CODE) {
                    usleep(100000); // Delays generation of new old_findsID to prevent further duplicate generation
                    $insertData['hoardID'] = $this->generateHoardId();
                    $i--;
                } else { // Any other Zend_Db_Exception
                    break;
                }
            }
        }
        if (isset($insert)) {
            return $insert;
        } else {
            return 'error';
        }
    }

    /** Edit a hoard record
     *
     * @param array $updateData
     * @param integer $id
     * @return int
     */
    public function editHoard(array $updateData, $id)
    {
        $id2by = $updateData['id2by'];
        if ($id2by === "" || is_null($id2by)) {
            $updateData['identifier2ID'] = NULL;
        }
        unset($updateData['recordername']);
        unset($updateData['idBy']);
        unset($updateData['id2by']);
        unset($updateData['hiddenfield']);
        unset($updateData['legacyID']);
        $updateData['materials'] = serialize($updateData['materials']);

        $where[0] = $this->getAdapter()->quoteInto('id = ?', $id);

        // Takes the arbitrarily long list of finders and prepares an array suitable
        // for updating/inserting into the Hoards_Finders table
        $findersData['finderID'] = $this->prepareFinders($updateData);
        // Removes the old finder ID values from the data to be inserted into Hoards table
        foreach ($updateData as $field => $value) {
            if (strpos($field, 'finder') !== false) {
                unset($updateData[$field]);
            }
        }
        // Adds the hoard ID to the array of finders for insertion into Hoards_Finders table
        $findersData['hoardID'] = $updateData['secuid'];
        $findersTable = new HoardsFinders();

        try {
            $update = $this->update($updateData, $where);
            $updateFinders = $findersTable->updateFinders($findersData);
        } catch (Zend_Db_Exception $e) {
            return 'error';
        }
        return $update;
    }

    /** Get all data from a hoard record for non-HTML rendering
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getAllHoardData($hoardId)
    {
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
            ->joinLeft('subsequentActions', 'hoards.subs_action = subsequentActions.id',
                array('subsequentAction' => 'action'))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get basic hoard information
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getBasicHoardData($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'id',
                'hoardID',
                'uniqueID' => 'secuid',
                'broadperiod',
                'secwfstage',
                'treasure',
                'recorderID',
                'created',
                'createdBy',
                'updated',
                'updatedBy',
                'institution'
            ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get the names the hoard is known as
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getKnownAs($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'hoardID' => 'secuid'
            ))
            ->joinLeft('findspots',
                'hoards.secuid = findspots.findID',
                array('knownas', 'alsoknownas'))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get general chronology of a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     * @todo Add date qualifier (circa etc.)
     */
    public function getChronology($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'broadperiod',
                'period1',
                'subPeriodFrom' => 'subperiod1',
                'period2',
                'subPeriodTo' => 'subperiod2',
                'numdate1',
                'numdate2'
            ))
            ->joinLeft('periods', 'hoards.period1 = periods.id',
                array('periodFrom' => 'term'))
            ->joinLeft(array('periods2' => 'periods'), 'hoards.period2 = periods2.id',
                array('periodTo' => 'term'))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get terminal date information of a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     * @todo Make this work properly for all periods of coinage
     */
    public function getCoinChronology($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'lastrulerID',
                'reeceID',
                'terminalDateFrom' => 'terminalyear1',
                'terminalDateTo' => 'terminalyear2',
                'terminalreason'
            ))
            ->joinLeft('rulers', 'hoards.lastrulerID = rulers.id',
                array(
                    'lastRuler' => 'issuer'
                ))
            ->joinLeft('reeceperiods', 'hoards.reeceID = reeceperiods.id',
                array(
                    'lastReecePeriod' => 'description'
                ))
            ->joinLeft('terminalreason', 'hoards.terminalreason = terminalreason.id',
                array(
                    'terminalReason' => 'reason'
                ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get hoard description and notes
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getHoardDescription($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'description',
                'notes',
                'findofnote',
                'findofnotereason'
            ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get quality rating of a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getQualityRating($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'qualityrating'
            ))
            ->joinLeft('dataquality', 'hoards.qualityrating = dataquality.id',
                array('coindataqualityrating' => 'rating'))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get what happened to the hoard after recording
     * @access public
     * @param integer $hoardId
     * @return array
     * @todo Implement use of accredited museums table for current location
     */
    public function getSubsequentActions($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'curr_loc',
                'subsequentAction' => 'subs_action'
            ))
            ->joinLeft('subsequentActions', 'hoards.subs_action = subsequentActions.id',
                array('subsequentActionTerm' => 'action'))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get any Treasure process details if the hoard is submitted for consideration as Treasure
     * @access public
     * @param integer $hoardId
     * @return array
     * @todo Add new Treasure functions here
     */
    public function getTreasureDetails($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'treasureID'
            ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get the materials a hoard is made of
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getMaterials($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'materials'))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        $materials = $this->getAdapter()->fetchRow($select);
        return unserialize($materials['materials']);
    }

    /** Get summary details of any coin records linked to a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     * @todo Add more specific COIN terms to inclusion
     */
    public function getLinkedCoins($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'hoardID' => 'secuid',
            ))
            ->joinLeft('finds',
                'hoards.secuid = finds.hoardID',
                array('id', 'old_findID', 'objecttype', 'broadperiod',
                    'treasureID', 'createdBy', 'institution'
                ))
            ->where('hoards.id = ?', (int)$hoardId)
            ->where('finds.objecttype IN (?)', $this->_coinarray);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);

    }

    /** Get summary details of any artefacts linked to a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     * @todo Add more specific COIN terms to exclusion
     */
    public function getLinkedArtefacts($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'hoardID' => 'secuid'
            ))
            ->joinLeft('finds',
                'hoards.secuid = finds.hoardID',
                array('id', 'old_findID', 'objecttype',  'broadperiod',
                    'treasureID', 'hoardcontainer', 'createdBy', 'institution'
                ))
            ->where('hoards.id = ?', (int)$hoardId)
            ->where('finds.objecttype NOT IN (?)', $this->_coinarray)
            ->where('finds.hoardcontainer IS NULL');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get summary details of any containers linked to a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     * @todo Add more specific COIN terms to exclusion
     */
    public function getLinkedContainers($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'hoardID' => 'secuid'
            ))
            ->joinLeft('finds',
                'hoards.secuid = finds.hoardID',
                array('id', 'old_findID', 'objecttype',  'broadperiod',
                    'treasureID', 'hoardcontainer', 'createdBy', 'institution'
                ))
            ->where('hoards.id = ?', (int)$hoardId)
            ->where('finds.hoardcontainer = 1');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get the recorders and identifiers of a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getRecordersIdentifiers($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'recorderID', 'identifier1ID',
                'identifier2ID'
            ))
            ->joinLeft(array('identifier1' => 'people'),
                'hoards.identifier1ID = identifier1.secuid', array(
                    'tit2' => 'title',
                    'fore2' => 'forename',
                    'sur2' => 'surname'
                ))
            ->joinLeft(array('identifier2' => 'people'),
                'hoards.identifier2ID = identifier2.secuid', array(
                    'tit5' => 'title',
                    'fore5' => 'forename',
                    'sur5' => 'surname'))
            ->joinLeft(array('recorder' => 'people'),
                'hoards.recorderID = recorder.secuid', array(
                    'tit3' => 'title',
                    'fore3' => 'forename',
                    'sur3' => 'surname'
                ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);

    }

    /** Get the finders of a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getFinders($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'hoardID' => 'secuid'
            ))
            ->joinLeft('hoards_finders',
                'hoards.secuid = hoards_finders.hoardID',
                array('finderID', 'order'))
            ->joinLeft('people',
                'hoards_finders.finderID = people.secuid',
                array('title', 'forename', 'surname'))
            ->where('hoards.id = ?', (int)$hoardId)
            ->order(array('order ASC'));
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);

    }

    /** Get the discovery details of a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getDiscoverySummary($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'disccircum',
                'discmethod',
                'datefound1',
                'datefound2'
            ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get reference numbers associated with a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getReferenceNumbers($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'treasureID',
                'legacyID',
                'other_ref',
                'smrrefno',
                'musaccno',
            ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get quantities associated with a hoard
     * @access public
     * @param integer $hoardId
     * @return array
     */
    public function getQuantities($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'quantityCoins',
                'quantityArtefacts',
                'quantityContainers'
            ))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Get images attached to a hoard record
     * @access public
     * @param integer $id
     * @return array
     * @todo Needs modification for Hoards
     */
    public function getImageToFind($id)
    {
        $key = md5('findtoimage' . $id);
        if (!$data = $this->_cache->load($key)) {

            $select = $this->select()
                ->from($this->_name, array(
                    'old_findID', 'broadperiod', 'objecttype'
                ))
                ->joinLeft('users', 'users.id = finds.createdBy',
                    array('imagedir'))
                ->joinLeft('finds_images',
                    'finds.secuid = finds_images.find_id',
                    array())
                ->joinLeft('slides',
                    'slides.secuid = finds_images.image_id',
                    array('i' => 'imageID', 'f' => 'filename'))
                ->where('finds.id= ?', (int)$id)
                ->order('slides.imageID ASC')
                ->limit(1);
            $select->setIntegrityCheck(false);
            $data = $this->getAdapter()->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }

    /** Get findID and secuid for linking images
     * @access public
     * @param string $q
     * @return array
     * @todo Needs modification for Hoards
     */
    public function getImageLinkData($q)
    {
        $select = $this->select()
            ->from($this->_name, array('term' => 'old_findID', 'id' => 'secuid'))
            ->where('old_findID LIKE ?', (string)$q . '%')
            ->limit(10);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get the last hoard record created by a specific user
     * @access public
     * @param integer $userId
     * @return array
     * @todo Needs modification for Hoards
     */
    public function getLastRecord($userId)
    {
        $fieldList = new CopyHoards();
        $fields = $fieldList->getConfig();
        $select = $this->select()
            ->from($this->_name, $fields)
            ->joinLeft(array('finderOne' => 'people'),
                'finderOne.secuid = hoards.finderID',
                array('finder' => 'fullname'))
            ->joinLeft(array('finderTwo' => 'people'),
                'finderTwo.secuid = hoards.finder2ID',
                array('secondfinder' => 'fullname'))
            ->joinLeft(array('identifier' => 'people'),
                'identifier.secuid = hoards.identifier1ID',
                array('idby' => 'fullname'))
            ->joinLeft(array('identifierTwo' => 'people'),
                'identifierTwo.secuid = hoards.identifier2ID',
                array('id2by' => 'fullname'))
            ->joinLeft(array('recorder' => 'people'),
                'recorder.secuid = hoards.finderID',
                array('recordername' => 'fullname'))
            ->where('hoards.createdBy = ?', (int)$userId)
            ->order('hoards.id DESC')
            ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve edit data for a hoard
     * @access public
     * @param integer $hoardID
     * @return array
     */
    public function getEditData($hoardId)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'id',
                'hoardID',
                'secuid',
                'period1',
                'subperiod1',
                'period2',
                'subperiod2',
                'numdate1',
                'numdate2',
                'broadperiod',
                'lastrulerID',
                'reeceID',
                'quantityCoins',
                'quantityArtefacts',
                'quantityContainers',
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
                'recorderID',
                'identifier1ID',
                'identifier2ID',
                'disccircum',
                'discmethod',
                'datefound1',
                'datefound2',
                'rally',
                'rallyID',
                'legacyID',
                'other_ref',
                'smrrefno',
                'musaccno',
                'curr_loc',
                'subs_action',
                'created',
                'createdBy',
                'updated',
                'updatedBy',
                'institution'
            ))
            ->joinLeft(array('ident1' => 'people'),
                'hoards.identifier1ID = ident1.secuid',
                array('idBy' => 'fullname'))
            ->joinLeft(array('ident2' => 'people'),
                'hoards.identifier2ID = ident2.secuid',
                array('id2by' => 'fullname'))
            ->joinLeft(array('record' => 'people'),
                'hoards.recorderID = record.secuid',
                array('recordername' => 'fullname'))
            ->where('hoards.id = ?', (int)$hoardId);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    public function copyLastHoard($userID)
    {
    }

    /** get data for embedding a find
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getEmbedHoard($hoardID)
    {
        $select = $this->select()
            ->from($this->_name)
            ->joinLeft('periods', 'hoards.period1 = periods.id',
                array('t' => 'term'))
            ->joinLeft('findspots', 'hoards.secuid = findspots.findID',
                array(
                    'gridref', 'easting', 'northing',
                    'parish', 'county', 'regionID',
                    'district', 'declat', 'declong',
                    'smrref', 'map25k', 'map10k',
                    'knownas'
                ))
            ->where('hoards.id= ?', (int)$hoardID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get data for citation of a hoard
     * @access public
     * @param integer $hoardID
     * @return array
     */
    public function getWebCiteHoard($hoardID)
    {
        $select = $this->select()
            ->from($this->_name, array(
                'broadperiod', 'id',
                'hoardID',
                'created' => 'DATE_FORMAT(hoards.created,"%Y")'
            ))
            ->joinLeft('periods', 'hoards.period1 = periods.id',
                array('t' => 'term'))
            ->joinLeft(array('record' => 'people'),
                'hoards.recorderID = record.secuid',
                array(
                    'tit3' => 'title',
                    'fore3' => 'forename',
                    'sur3' => 'surname'
                ))
            ->where('hoards.id= ?', (int)$hoardID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get data for solr updater
     * @access public
     * @param integer $hoardID
     * @return array
     * @todo add in foreign tables for joins
     */
    public function getSolrData( $hoardID )
    {
        $select = $this->select()
            ->from($this->_name)
            ->where($this->_name . '.id = ?', $hoardID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }
}