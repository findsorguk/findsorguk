<?php
/** Access, manipulate and delete finds data. I wrote this when I was
 * a naive new php programmer (still am really!). It sucks in a massive way.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Finds();
 * $data = $model->getAllData($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @author Mary Chester-Kadwell <mchester-kadwell@britishmuseum.org>
 * @copyright 2010 - DEJ Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo needs a complete overhaul. Lots of duplication.
 * @example /app/modules/database/controllers/ArtefactsController.php
*/
class Finds extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'finds';

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

    /** The duplicate value error code
     * 
     */
    const DUPLICATE_UNIQUE_VALUE_ERROR_CODE = 23000;
    
    
     /** Generates an old_findsID for new find records
     * @access 	public
     * @return	string $findid The old_findsID
     * @throws  Pas_Exception_NotAuthorised
     */
    public function generateFindId() {
        $institution = $this->getInstitution();
        if(!is_null($institution)) {
            list($usec, $sec) = explode(" ", microtime());
            $suffix =  strtoupper(substr(dechex($sec), 3) . dechex(round($usec * 15)));
            $findid = $institution . '-' . $suffix;
            return $findid;
        } else {
            throw new Pas_Exception_Group('Institution missing', 500);
        }
    }
    
    
     /** Add a new find record
     * @param array
     * @return int
     */
    public function addFind($insertData){
        $insertData['secuid'] = $this->generateSecuId();
        $insertData['old_findID'] = $this->generateFindId();
        $insertData['secwfstage'] = (int)2;
        $insertData['institution'] = $this->getInstitution();
        unset($insertData['recordername']);
        unset($insertData['finder']);
        unset($insertData['idBy']);
        unset($insertData['id2by']);
        unset($insertData['secondfinder']);

        $i = 2;
        while ($i > 0){
            try {
                $insert = $this->add($insertData);
                break;
            } catch(Zend_Db_Exception $e) {
                $code = $e->getCode();
                // If there is a duplicate unique value, generates a new old_findsID and tries again up to twice
                if($code == self::DUPLICATE_UNIQUE_VALUE_ERROR_CODE) {
                    usleep(100000); // Delays generation of new old_findsID to prevent further duplicate generation
                    $insertData['old_findID'] = $this->generateFindId();
                    $i--;
                } else { // Any other Zend_Db_Exception
                    break;
                }
            }
        }
        if(isset($insert)){
            return $insert;
        } else {
            return 'error';
        }
    }
    
     /** Edit a find record
      * 
      * @param array $updateData
      * @param integer $id
      * @return int
      */
    public function editFind(array $updateData, $id){
        $id2by = $updateData['id2by'];
        if($id2by === "" || is_null($id2by)){
            $updateData['identifier2ID'] = NULL;
        }
        unset($updateData['recordername']);
        unset($updateData['finder']);
        unset($updateData['idBy']);
        unset($updateData['id2by']);
        unset($updateData['secondfinder']);

        $where[0] = $this->getAdapter()->quoteInto('id = ?', $id);

        return $this->update($updateData, $where);
    }

    /** Get id of find from secuid
     *
     * @param string $secuid
     * @return int
     */
    public function getIdFromSecuid($secuid){
        $select = $this->select()
            ->from($this->_name, array('id'))
            ->where('secuid = ?', $secuid)
            ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

    /** Link a find record to a hoard record
     *
     * @param array $updateData
     * @param integer $id
     * @return int
     */
    public function linkFind(array $updateData, $id){
        $where[0] = $this->getAdapter()->quoteInto('id = ?', $id);
        return $this->update($updateData, $where);
    }

    /** Unlink a find record to a hoard record
     *
     * @param array $updateData
     * @param integer $id
     * @return int
     */
    public function unlinkFind($id){
        $where[0] = $this->getAdapter()->quoteInto('id = ?', $id);
        return $this->update(array('hoardID' => NULL), $where);
    }

    /** Get a find's secure unique id for the jquery autocomplete
     * @access public
     * @param string $q
     * @return array
     */
    public function getFindSecuid($q) {
        $select = $this->select()
                ->from($this->_name, array(
                    'id' => 'secuid',
                    'term' => 'CONCAT(old_findID," - ",objecttype," ","(",broadperiod,")")'))
                ->order($this->_primary)
                ->where('old_findID LIKE ?', (string) $q . '%')
                ->limit(10);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /**  Get a count of all the finds by specific workflow stage
     * @access public
     * @param integer $wfStageID
     * @return array
     */
    public function getWorkflowstatus($wfStageID) {
        $select = $this->select()
                ->from($this->_name, array('secwfstage'))
                ->joinLeft('workflowstages',
                        'finds.secwfstage = workflowstages.id',
                        array('workflowstage'))
                ->where('finds.id = ?', (int)$wfStageID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get massive data for a single record, loads of joins
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getAllData($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'id', 'old_findID', 'uniqueID' => 'secuid',
                    'objecttype', 'classification', 'subclass',
                    'length', 'height', 'width', 'weight',
                    'thickness', 'diameter', 'quantity',
                    'other_ref', 'treasureID', 'broadperiod',
                    'numdate1', 'numdate2', 'description',
                    'notes', 'reuse', 'created' =>'finds.created',
                    'broadperiod', 'updated', 'treasureID',
                    'secwfstage', 'findofnote', 'objecttypecert',
                    'datefound1', 'datefound2', 'inscription',
                    'disccircum', 'museumAccession' => 'musaccno',
                    'subsequentAction' => 'subs_action',
                    'objectCertainty' => 'objecttypecert',
                    'dateFromCertainty' => 'numdate1qual',
                    'dateToCertainty' => 'numdate2qual',
                    'dateFoundFromCertainty' => 'datefound1qual',
                    'dateFoundToCertainty' => 'datefound2qual',
                    'subPeriodFrom' => 'objdate1subperiod',
                    'subPeriodTo' => 'objdate2subperiod',
                    'secuid'
                    ))
                ->joinLeft('findofnotereasons',
                        'finds.findofnotereason = findofnotereasons.id',
                        array('reason' => 'term'))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('username','fullname','institution'))
                ->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy',
                        array(
                            'usernameUpdate' => 'username',
                            'fullnameUpdate' => 'fullname'
                            ))
                ->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id',
                        array('primaryMaterial' =>'term'))
                ->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id',
                        array('secondaryMaterial' => 'term'))
                ->joinLeft('decmethods','finds.decmethod = decmethods.id',
                        array('decoration' => 'term'))
                ->joinLeft('decstyles','finds.decstyle = decstyles.id',
                        array('style' => 'term'))
                ->joinLeft('manufactures','finds.manmethod = manufactures.id',
                        array('manufacture' => 'term'))
                ->joinLeft('surftreatments','finds.surftreat = surftreatments.id',
                        array('surfaceTreatment' => 'term'))
                ->joinLeft('completeness','finds.completeness = completeness.id',
                        array('completeness' => 'term'))
                ->joinLeft('preservations','finds.preservation = preservations.id',
                        array('preservation' => 'term'))
                ->joinLeft('certaintytypes','certaintytypes.id = finds.objecttypecert',
                        array('cert' => 'term'))
                ->joinLeft('periods','finds.objdate1period = periods.id',
                        array('periodFrom' => 'term'))
                ->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id',
                        array('periodTo' => 'term'))
                ->joinLeft('cultures','finds.culture = cultures.id',
                        array('culture' => 'term'))
                ->joinLeft('discmethods','discmethods.id = finds.discmethod',
                        array('discmethod' => 'method'))
                ->joinLeft('people','finds.finderID = people.secuid',
                        array('finder' => 'CONCAT(people.title," ",people.forename," ",people.surname)'))
                ->joinLeft(array('ident1' => 'people'),'finds.identifier1ID = ident1.secuid',
                        array('identifier' => 'CONCAT(ident1.title," ",ident1.forename," ",ident1.surname)'))
                ->joinLeft(array('ident2' => 'people'),'finds.identifier2ID = ident2.secuid',
                        array('secondaryIdentifier' => 'CONCAT(ident2.title," ",ident2.forename," ",ident2.surname)'))
                ->joinLeft(array('record' => 'people'),'finds.recorderID = record.secuid',
                        array('recorder' => 'CONCAT(record.title," ",record.forename," ",record.surname)'))
                ->joinLeft('findspots','finds.secuid = findspots.findID',
                        array(
                            'county', 'parish', 'district',
                            'easting', 'northing', 'gridref',
                            'fourFigure', 'map25k', 'map10k',
                            'address', 'postcode', 'findspotdescription' => 'description',
                            'lat' => 'declat', 'lon' => 'declong', 'knownas',
                            'fourFigureLat', 'fourFigureLon', 'geohash',
                            'woeid'
                            ))
                ->joinLeft('gridrefsources',
                        'gridrefsources.ID = findspots.gridrefsrc',
                        array('source' => 'term'))
                ->joinLeft('coins','finds.secuid = coins.findID',
                        array(
                            'obverse_description', 'obverse_inscription',
                            'reverse_description', 'reverse_inscription',
                            'denominationID' => 'denomination',
                            'degree_of_wear', 'allen_type', 'va_type',
                            'mack' => 'mack_type', 'reeceID',
                            'die' => 'die_axis_measurement',
                            'wearID'=> 'degree_of_wear', 'moneyer', 'revtypeID',
                            'categoryID', 'typeID', 'tribeID' => 'tribe',
                            'status', 'rulerQualifier' => 'ruler_qualifier',
                            'denominationQualifier' => 'denomination_qualifier',
                            'mintQualifier' => 'mint_qualifier',
                            'dieAxisCertainty' => 'die_axis_certainty',
                            'initialMark' => 'initial_mark',
                            'reverseMintMark' => 'reverse_mintmark',
                            'statusQualifier' => 'status_qualifier',
                            'ruler_id',
                            'mint_id'
                            ))
                ->joinLeft('ironagetribes','coins.tribe = ironagetribes.id',
                        array('tribe'))
                ->joinLeft('geographyironage','geographyironage.id = coins.geographyID',
                        array('region','area'))
                ->joinLeft('denominations','denominations.id = coins.denomination',
                        array(
                            'denomination',
                            'nomismaDenomination' => 'nomismaID',
                            'dbpediaDenomination' => 'dbpediaID',
                            'bmDenomination' => 'bmID'
                            ))
                ->joinLeft('rulers','rulers.id = coins.ruler_id',
                        array(
                            'ruler1' => 'issuer',
                            'viaf',
                            'rulerDbpedia' => 'dbpedia',
                            'nomismaRulerID' => 'nomismaID'
                            ))
                ->joinLeft(array('rulers_2' => 'rulers'),
                        'rulers_2.id = coins.ruler2_id',
                        array('ruler2' => 'issuer'))
                ->joinLeft('reeceperiods','coins.reeceID = reeceperiods.id',
                        array('period_name','date_range'))
                ->joinLeft('mints','mints.id = coins.mint_ID',
                        array(
                            'mint_name',
                            'nomismaMintID' => 'nomismaID',
                            'pleiadesID',
                            'mintGeonamesID' => 'geonamesID',
                            'mintWoeid' => 'woeid'
                            ))
                ->joinLeft('weartypes',
                        'coins.degree_of_wear = weartypes.id',
                        array('wear' => 'term'))
                ->joinLeft('dieaxes','coins.die_axis_measurement = dieaxes.id',
                        array('die_axis_name'))
                ->joinLeft('medievalcategories',
                        'medievalcategories.id = coins.categoryID',
                        array('category'))
                ->joinLeft('medievaltypes','medievaltypes.id = coins.typeID',
                        array('type'))
                ->joinLeft('moneyers','moneyers.id = coins.moneyer',
                        array('moneyer' => 'name'))
                ->joinLeft('emperors','emperors.pasID = rulers.id',
                        array('emperorID' => 'id'))
                ->joinLeft('romanmints','romanmints.pasID = mints.id',
                        array('mintid' => 'id'))
                ->joinLeft('revtypes','coins.revtypeID = revtypes.id',
                        array('reverseType' => 'type'))
                ->joinLeft('statuses','coins.status = statuses.id',
                        array('status' => 'term'))
                ->joinLeft('finds_images','finds.secuid = finds_images.find_id',
                        array())
                ->joinLeft('slides','slides.secuid = finds_images.image_id',
                        array('thumbnail' => 'imageID', 'filename'))
                ->joinLeft(array('u' => 'users'),'slides.createdBy = u.id',
                        array('imagedir'))
                ->joinLeft('regions', 'findspots.regionID = regions.id',
                        array('region'))
                ->where('finds.id = ?', (int)$findID)
                ->group('finds.id')
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get dimensional data for a find
    * @param integer $findID the find number
    * @return array
    * @todo cache the output
    */
    public function getFindData($findID){
        $select = $this->select()
                ->from($this->_name, array(
                    'length', 'height', 'width',
                    'thickness', 'diameter', 'quantity',
                    'weight'
                    ))
                ->where('finds.id = ?', (int)$findID);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get reference data for a find
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getFindOtherRefs($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'other_ref', 'treasureID', 'smr_ref',
                    'musaccno'
                    ))
                ->where('finds.id = ?', (int)$findID);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get materials and manufacturing data for a find
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getFindMaterials($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'material1', 'material2', 'manmethod',
                    'decmethod' ,'decstyle', 'completeness',
                    'surftreat'
                    ))
                ->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id',
                        array('mat1' =>'term'))
                ->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id',
                        array('mat2' => 'term'))
                ->joinLeft('decmethods','finds.decmethod = decmethods.id',
                        array('decoration' => 'term'))
                ->joinLeft('decstyles','finds.decstyle = decstyles.id',
                        array('style' => 'term'))
                ->joinLeft('manufactures','finds.manmethod = manufactures.id',
                        array('manufacture' => 'term'))
                ->joinLeft('surftreatments','finds.surftreat = surftreatments.id',
                        array('surface' => 'term'))
                ->joinLeft('completeness','finds.completeness = completeness.id',
                        array('complete' => 'term'))
                ->joinLeft('certaintytypes','certaintytypes.id = finds.objecttypecert',
                        array('cert' => 'term'))
                ->where('finds.id = ?', (int)$findID)
                ->group('finds.id')
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get temporal data for a find
    * @param integer $findID the find number
    * @return array
    * @todo cache the output
    */
    public function getFindTemporalData($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'broadperiod',
                    'numdate1',
                    'numdate2',
                    'period1' => 'objdate1period',
                    'period2' => 'objdate2period',
                    'culture',
                    'subPeriodFrom' => 'objdate1subperiod',
                    'subPeriodTo' => 'objdate2subperiod'
                    ))
                ->joinLeft('periods','finds.objdate1period = periods.id',
                        array('term'))
                ->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id',
                        array('t2' => 'term'))
                ->joinLeft('cultures','finds.culture = cultures.id',
                        array('cult' => 'term'))
                ->joinLeft(array('circa1' => 'datequalifiers'), $this->_name
                        . '.numdate1qual = circa1.id',
                        array('fromcirca' => 'term'))
                ->joinLeft(array('circa2' => 'datequalifiers'), $this->_name
                        . '.numdate2qual = circa2.id',
                        array('tocirca' => 'term'))
                ->where('finds.id = ?', (int)$findID)
                ->group('finds.id')
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get personal data for a find
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getPersonalData($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'finderID', 'recorderID', 'identifier1ID',
                    'identifier2ID'
                    ))
                ->joinLeft('people','finds.finderID = people.secuid',
                        array(
                            'tit1' => 'title',
                            'fore' => 'forename',
                            'sur' => 'surname','secuid'
                            ))
                ->joinLeft(array('identifier1' => 'people'),
                        'finds.identifier1ID = identifier1.secuid', array(
                            'tit2' => 'title',
                            'fore2' => 'forename',
                            'sur2' => 'surname'
                            ))
                ->joinLeft(array('identifier2' => 'people'),
                        'finds.identifier2ID = identifier2.secuid', array(
                            'tit5' => 'title',
                            'fore5' => 'forename',
                            'sur5' => 'surname'))
                ->joinLeft(array('recorder' => 'people'),
                        'finds.recorderID = recorder.secuid', array(
                            'tit3' => 'title',
                            'fore3' => 'forename',
                            'sur3' => 'surname'
                            ))
                ->where('finds.id = ?',$findID)
                ->group('finds.id')
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }


    /** Get finds entered by user per quarter as a count and sum
     * @access public
     * @param integer $staffID
     * @return array
     */
    public function getFindsFloQuarter($staffID){
        $select = $this->select()
                ->from($this->_name, array(
                    'year' => 'EXTRACT(YEAR FROM finds.created)',
                    'artefacts' => 'SUM(quantity)',
                    'records' => 'COUNT(*)',
                    'quarter' => 'QUARTER(finds.created)'
                    ))
                ->joinLeft('staff','staff.dbaseID = finds.createdBy', array())
                ->where('staff.id = ?',(int)$staffID)
                ->order(array('year','quarter'))
                ->group('quarter')
                ->group('year');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get finds entered by user per broadperiod as a count and sum
    * @param integer $staffID The user's ID
    * @return array
    */
    public function getFindsFloPeriod($staffID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'artefacts' => 'SUM(quantity)',
                    'records' => 'COUNT(*)',
                    'broadperiod'
                    ))
                ->joinLeft('staff','staff.dbaseID = finds.createdBy',
                        array('id' => 'dbaseID' ))
                ->where('broadperiod IS NOT NULL')
                ->where('staff.id = ?',(int)$staffID)
                ->group('broadperiod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }


    /** Get all finds by a day
     * @access public
     * @return array
     */
    public function getFindsByDay() {
        $select = $this->select()
                ->from($this->_name, array(
                    'createdOn' => 'DATE_FORMAT(finds.created,"%Y-%m-%d")'
                    ))
                ->group('createdOn');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get total for reports by date
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getReportTotals($datefrom, $dateto){
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->where('created >= ?', (string)$datefrom)
                ->where('created <= ?', (string)$dateto);
        $select->setIntegrityCheck(false);
       return $this->getAdapter()->fetchAll($select);
    }

    /** Get finds officer totals by fullname for reports by date
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getOfficerTotals($datefrom, $dateto){
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('fullname','institution','id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string) $dateto)
                ->order('institution','fullname')
                ->group('fullname','institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get institutional totals for reports by date
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getInstitutionTotals($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('institution'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('institution')
                ->group('institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get broadperiod totals for reports by date
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getPeriodTotals($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod'
                    ))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->group('broadperiod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get distinct finder totals for reports by date
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getFindersTotals($datefrom,$dateto) {
        $select = $this->select()
                ->from($this->_name, array())
                ->joinLeft('people','people.secuid = finds.finderID', array(
                    'finders' => 'COUNT(DISTINCT(finderID))'
                    ))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('institution'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('institution')
                ->group('institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get monthly count of finds found
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getAverageMonth($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array('records' => 'COUNT(finds.id)', 'finds' => 'SUM(quantity)','broadperiod',
                'month' => 'EXTRACT(MONTH FROM created)'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('month ASC')
                ->group('month');
        $select->setIntegrityCheck(false);
        return $this->getAdapter->fetchAll($select);
    }

    /** Get discovery year for finds found between certain dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getYearFound($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod',
                    'year' => 'EXTRACT(YEAR FROM datefound1)'
                    ))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('year ASC')
                ->group('year');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get discovery method counts for finds found between certain dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getDiscoveryMethod($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('discmethods','discmethods.id = finds.discmethod',
                        array('discmethod' => 'method','id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('discmethod')
                ->group('discmethod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get landuse counts for finds found between certain dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getLandUse($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->joinLeft('landuses','landuses.id = findspots.landusevalue',
                        array('landuse' => 'term'))
                ->where($this->_name.'.created >= ?', (string)$datefrom)
                ->where($this->_name.'.created <= ?', (string)$dateto)
                ->order('landuse')
                ->group('landuse');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get landuse counts for finds found between certain dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getPrecision($datefrom,$dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array('precision' => 'gridlen'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('precision')
                ->group('precision');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get landuse counts for finds found between certain dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getCounties($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots', $this->_name
                        . '.secuid = findspots.findID', array('county'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('county')
                ->group('county');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get counts for finds found between certain dates for counties
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getCountyStat($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array('county'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('findspots.county = ?',(string)$county)
                ->order('county')
                ->group('county');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get counts for finds found between certain dates for counties by
     * specific user
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getUsersStat($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array())
                ->joinLeft('users',$this->_name
                        . '.createdBy = users.id',
                        array('fullname','username','institution','id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('findspots.county = ?',(string)$county)
                ->order('institution')
                ->group('fullname');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get counts for finds found between certain dates for periods and county
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getPeriodTotalsCounty($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('findspots.county = ?', (string)$county)
                ->group('broadperiod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter->fetchAll($select);
    }

    /** Get counts for finders by county
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getFinderTotalsCounty($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array())
                ->joinLeft('people','people.secuid = finds.finderID',
                        array(
                            'finders' => 'COUNT(DISTINCT(finderID))'
                            ))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('institution'))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('findspots.county = ?', (string)$county)
                ->order('institution')
                ->group('institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get finds per month by county
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getAverageMonthCounty($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod',
                    'month' => 'EXTRACT(MONTH FROM ' . $this->_name . '.created)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array())
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('findspots.county = ?', (string)$county)
                ->order('month ASC')
                ->group('month');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get finds per year by county
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param strig $county
     * @return array
     */
    public function getYearFoundCounty($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod',
                    'year' => 'EXTRACT(YEAR FROM datefound1)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('findspots.county = ?', (string)$county)
                ->order('year ASC')
                ->group('year');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get discovery method totals by county
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getDiscoveryMethodCounty($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->joinLeft('discmethods','discmethods.id = finds.discmethod',
                        array('discmethod' => 'method','id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('findspots.county = ?', (string)$county)
                ->order('discmethod')
                ->group('discmethod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get landuse totals by county
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getLandUseCounty($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(finds.id)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->joinLeft('landuses','landuses.id = findspots.landusevalue',
                        array('landuse' => 'term','id'))
                ->where('findspots.county = ?', (string)$county)
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('landuse')
                ->group('landuse');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get precision of findspot by county
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $county
     * @return array
     */
    public function getPrecisionCounty($datefrom, $dateto, $county) {
        $select = $this->select()
                ->from($this->_name,
                        array('records' => 'COUNT(*)',
                            'finds' => 'SUM(quantity)'
                            ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array('precision' => 'gridlen'))
                ->where('findspots.county = ?',(string)$county)
                ->where($this->_name.'.created >= ?', (string)$datefrom)
                ->where($this->_name.'.created <= ?', (string)$dateto)
                ->order('precision')
                ->group('precision');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get recording institutions between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getInstitutions($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name,
                        array(
                            'records' => 'COUNT(*)',
                            'finds' => 'SUM(quantity)'
                            ))
                ->joinLeft('users',$this->_name
                        . '.createdBy = users.id',
                        array('institution'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('institution')
                ->group('institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get  institution's recording stats between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getInstStat($datefrom,$dateto,$institution) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('users',$this->_name
                        . '.createdBy = users.id',
                        array('institution'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('users.institution = ?', (string)$institution)
                ->order('institution')
                ->group('institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }


    /** Get institution's recording user stats between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getUsersInstStat($datefrom,$dateto,$institution){
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('users',$this->_name
                        . '.createdBy = users.id',
                        array('fullname','username','institution','id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('users.institution = ?',(string)$institution)
                ->order('institution')
                ->group('fullname');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get institution's recording period totals between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getPeriodTotalsInst($datefrom,$dateto,$institution) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)','broadperiod'
                    ))
                ->joinLeft('users',$this->_name
                        . '.createdBy = users.id',
                        array('fullname','username','institution','id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('users.institution = ?',(string)$institution)
                ->order('broadperiod')
                ->group('broadperiod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get institution's number of finders between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getFinderTotalsInst($datefrom, $dateto, $institution) {
        $select = $this->select()
                ->from($this->_name, array())
                ->joinLeft('people','people.secuid = finds.finderID',
                        array('finders' => 'COUNT(DISTINCT(finderID))'))
                ->joinLeft('users','users.id = finds.createdBy',array('institution'))
                ->where($this->_name.'.created >= ?', $datefrom)
                ->where($this->_name.'.created <= ?', $dateto)
                ->where('users.institution = ?',(string)$institution)
                ->order('institution')
                ->group('institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
        }

    /** Get institution's year of discovery range between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getYearFoundInst($datefrom, $dateto, $institution) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod',
                    'year' => 'EXTRACT(YEAR FROM datefound1)'
                    ))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('institution'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('users.institution = ?',(string)$institution)
                ->order('year ASC')
                ->group('year');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get institution's method of discovery range between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getDiscoveryMethodInst($datefrom, $dateto, $institution) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('discmethods','discmethods.id = finds.discmethod',
                        array('discmethod' => 'method'))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('institution'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('users.institution = ?', (string)$institution)
                ->order('discmethod')
                ->group('discmethod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get institution's land uses range between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getLandUseInst($datefrom, $dateto, $institution) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('institution'))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array())
                ->joinLeft('landuses','landuses.id = findspots.landusevalue',
                        array('landuse' => 'term','id'))
                ->where('users.institution = ?', (string)$institution)
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('landuse')
                ->group('landuse');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get institution's land uses range between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getPrecisionInst($datefrom, $dateto, $institution) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array('precision' => 'gridlen'))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('institution'))
                ->where('users.institution = ?', (string)$institution)
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('precision')
                ->group('precision');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get institution's monthly records and sum recorded between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param string $institution
     * @return array
     */
    public function getAverageMonthInst($datefrom, $dateto, $institution) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod',
                    'month' => 'EXTRACT(MONTH FROM finds.created)'
                    ))
                ->joinLeft('users','finds.createdBy = users.id',
                        array('fullname'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('users.institution = ?', (string)$institution)
                ->order('month')
                ->group('month');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get recording regions between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @return array
     */
    public function getRegions($datefrom, $dateto) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array())
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label' , 'id' => 'osID'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('region')
                ->group('region');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get regions' figures between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param integer $regionID
     * @return array
     */
    public function getRegionStat($datefrom, $dateto, $regionID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array('county'))
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label', 'id' => 'osID'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('osRegions.osID= ?',(int)$regionID)
                ->order('county')
                ->group('county');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get users recording by a region between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param integer $regionID
     * @return array
     */
    public function getUsersRegionStat($datefrom, $dateto, $regionID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label'))
                ->joinLeft('users',$this->_name . '.createdBy = users.id',
                        array('fullname', 'username', 'institution', 'id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('osRegions.osID = ?', (integer)$regionID)
                ->order('institution')
                ->group('fullname');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get broadperiods by a region between dates
    * @param string $datefrom The first date
    * @param string $dateto The second date
    * @param integer $regionID The recording region
    * @return array
    */
    public function getPeriodTotalsRegion($datefrom,$dateto,$regionID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array())
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label','id' => 'osID'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('osRegions.osID = ?', (integer)$regionID)
                ->order('broadperiod')
                ->group('broadperiod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get finder totals by a region between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param integer $regionID
     * @return array
     */
    public function getFinderTotalsRegion($datefrom, $dateto, $regionID) {
        $select = $this->select()
                ->from($this->_name, array())
                ->joinLeft('people','people.secuid = finds.finderID',
                        array('finders' => 'COUNT(DISTINCT(finderID))'))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID',
                        array('county'))
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label'))
                ->joinLeft('users',$this->_name . '.createdBy = users.id',
                        array('fullname', 'username', 'institution','id'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('osRegions.osID = ?', (string)$regionID)
                ->order('institution')
                ->group('institution');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get year of discovery by a region between dates
    * @param string $datefrom The first date
    * @param string $dateto The second date
    * @param integer $regionID The recording region
    * @return array
    */
    public function getYearFoundRegion($datefrom,$dateto,$regionID) {
        $select = $this->select()
                ->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)','broadperiod',
                'year' => 'EXTRACT(YEAR FROM datefound1)'))
                ->joinLeft('findspots',$this->_name.'.secuid = findspots.findID', array('county'))
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID', array('region' => 'label','id' => 'osID'))
                ->where($this->_name.'.created >= ?', $datefrom)
                ->where($this->_name.'.created <= ?', $dateto)
                ->where('osRegions.osID = ?',(integer)$regionID)
                ->order('year ASC')
                ->group('year');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get finder totals by a region between dates
    * @param string $datefrom The first date
    * @param string $dateto The second date
    * @param integer $regionID The recording region
    * @return array
    */
    public function getDiscoveryMethodRegion($datefrom,$dateto,$regionID) {
        $select = $this->select()
                ->from($this->_name, array('records' => 'COUNT(*)', 'finds' => 'SUM(quantity)'))
                ->joinLeft('discmethods','discmethods.id = finds.discmethod', array('discmethod' => 'method','id'))
                ->joinLeft('findspots',$this->_name . '.secuid = findspots.findID', array('county'))
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID', array('region' => 'label'))
                ->where($this->_name . '.created >= ?', $datefrom)
                ->where($this->_name . '.created <= ?', $dateto)
                ->where('osRegions.osID = ?', (integer)$regionID)
                ->order('discmethod')
                ->group('discmethod');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get landuse totals by a region between dates
    * @param string $datefrom The first date
    * @param string $dateto The second date
    * @param integer $regionID The recording region
    * @return array
    */
    public function getLandUseRegion($datefrom, $dateto, $regionID){
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array('county'))
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label'))
                ->joinLeft('landuses','landuses.id = findspots.landusevalue',
                        array('landuse' => 'term'))
                ->where('osRegions.osID = ?',(integer)$regionID)
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('landuse')
                ->group('landuse');
        $select->setIntegrityCheck(false);
       return $this->getAdapter()->fetchAll($select);
    }

    /** Get findspot precision by a region between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param integer $regionID
     * @return array
     */
    public function getPrecisionRegion($datefrom, $dateto, $regionID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)'
                    ))
                ->joinLeft('findspots',$this->_name.'.secuid = findspots.findID',
                        array('precision' => 'gridlen'))
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label'))
                ->where('osRegions.osID = ?', (integer)$regionID)
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->order('precision')
                ->group('precision');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get monthly totals by region between dates
     * @access public
     * @param string $datefrom
     * @param string $dateto
     * @param integer $regionID
     * @return array
     */
    public function getAverageMonthRegion($datefrom, $dateto, $regionID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'records' => 'COUNT(*)',
                    'finds' => 'SUM(quantity)',
                    'broadperiod',
                    'month' => 'EXTRACT(MONTH FROM finds.created)'
                    ))
                ->joinLeft('findspots',$this->_name
                        . '.secuid = findspots.findID', array('county'))
                ->joinLeft('osRegions','findspots.regionID = osRegions.osID',
                        array('region' => 'label'))
                ->where($this->_name . '.created >= ?', (string)$datefrom)
                ->where($this->_name . '.created <= ?', (string)$dateto)
                ->where('osRegions.osID = ?', (integer)$regionID)
                ->order('month')
                ->group('month');
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Check if a findspot exists
     * @access public
     * @param integer $findspotID
     * @return array
     */
    public function getFindtoFindspots($findspotID) {
        $select = $this->select()
                ->from($this->_name)
                ->joinLeft('findspots','finds.secuid = findspots.findID',
                        array())
                ->where('findspots.id = ?' ,(int)$findspotID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** get data for embedding a find
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getEmbedFind($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'broadperiod', 'id', 'objecttype',
                    'old_findID'
                    ))
                ->joinLeft('periods','finds.objdate1period = periods.id',
                        array('t' => 'term'))
                ->joinLeft('findspots','finds.secuid = findspots.findID',
                        array(
                            'gridref', 'easting', 'northing',
                            'parish', 'county', 'regionID',
                            'district', 'declat', 'declong',
                            'smrref', 'map25k', 'map10k',
                            'knownas'
                            ))
                ->where('finds.id= ?',(int)$findID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** gGt data for citation of a find
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getWebCiteFind($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'broadperiod', 'id', 'objecttype',
                    'old_findID', 'created' => 'DATE_FORMAT(finds.created,"%Y")'
                    ))
                ->joinLeft('periods','finds.objdate1period = periods.id',
                        array('t' => 'term'))
                ->joinLeft(array('record' => 'people'),
                        'finds.recorderID = record.secuid',
                        array(
                            'tit3' => 'title',
                            'fore3' => 'forename',
                            'sur3' => 'surname'
                            ))
                ->where('finds.id= ?',(int)$findID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve edit data for a find
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getEditData($findID)  {
        $select = $this->select()
                ->from($this->_name)
                ->joinLeft('people','finds.finderID = people.secuid',
                        array('finder'  => 'fullname'))
                ->joinLeft(array('people2' => 'people'),
                        'finds.finder2ID = people2.secuid',
                        array('secondfinder' => 'fullname'))
                ->joinLeft(array('ident1' => 'people'),
                        'finds.identifier1ID = ident1.secuid',
                        array('idBy' => 'fullname'))
                ->joinLeft(array('ident2' => 'people'),
                        'finds.identifier2ID = ident2.secuid',
                        array('id2by' => 'fullname'))
                ->joinLeft(array('record' => 'people'),
                        'finds.recorderID = record.secuid',
                        array('recordername' => 'fullname'))
                ->where('finds.id = ?', (int)$findID)
                ->group('finds.id')
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve find's numbers and broadperiod
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getFindNumbersEtc($findID) {
        $select = $this->select()
                ->from($this->_name, array(
                    'objecttype', 'id', 'broadperiod',
                    'old_findID'
                    ))
                ->where('finds.id = ?', (int)$findID)
                ->limit(1);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve finds adviser responsible
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getRelevantAdviserFind($findID){
        $select = $this->select()
                ->from($this->_name,array(
                    'objecttype', 'id', 'broadperiod',
                    'old_findID', 'secuid', 'institution',
                    'createdBy', 'created'
                    ))
                ->joinLeft('findspots','finds.secuid = findspots.findID',
                        array('county'))
                ->where('finds.id = ?', (int)$findID)
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve find data if allowed access
     * @access public
     * @param integer $findID
     * @param string $role
     * @return array
     */
    public function getIndividualFind($findID, $role) {
        $select = $this->select()
                ->from($this->_name, array(
                    'created2' => 'DATE_FORMAT(finds.created,"%Y %m %d")',
                    'description',
                    'notes',
                    'old_findID',
                    'id',
                    'objecttype',
                    'classification',
                    'subclass',
                    'reuse',
                    'created' =>'finds.created',
                    'broadperiod',
                    'updated',
                    'treasureID',
                    'secwfstage',
                    'secuid',
                    'findofnote',
                    'objecttypecert',
                    'datefound1',
                    'datefound2',
                    'createdBy',
                    'curr_loc',
                    'hoardcontainer',
                    'inscription',
                    'institution'))
                ->joinLeft('findofnotereasons',
                        'finds.findofnotereason = findofnotereasons.id',
                        array('reason' => 'term'))
                ->joinLeft('subsequentActions',
                        'finds.subs_action = subsequentActions.id',
                        array('subsequentAction' => 'action'))
                ->where('finds.id= ?',(int)$findID);
        if(in_array($role, $this->_restricted)) {
            $select->where(new Zend_Db_Expr(
                    'finds.secwfstage IN ( 3, 4) OR finds.createdBy = '
                    . (int)$this->getUserNumber()
                    )
                );
        }
        $select->setIntegrityCheck(false);
        return  $this->getAdapter()->fetchAll($select);
    }

    /** Get attached images
     * @access public
     * @param integer $id
     * @return array
     */
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

    /** Get the last record created by a specific user
     * @access public
     * @param integer $userid
     * @return array
     */
    public function getLastRecord($userid) {
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
                ->where('finds.createdBy = ?', (int)$userid)
                ->order('finds.id DESC')
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get findID and secuid for linking images
     * @access public
     * @param string $q
     * @return array
     */
    public function getImageLinkData($q) {
        $select = $this->select()
                ->from($this->_name, array('term' => 'old_findID','id' => 'secuid'))
                ->where('old_findID LIKE ?', (string)$q . '%')
                ->limit(10);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Get creator of a record by ID number
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getCreator($findID) {
        $select = $this->select()
                ->from($this->_name, array('old_findID','objecttype'))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('email','fullname'))
                ->where('finds.id = ?', (int)$findID);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve record data in format for solr schema
     * @access public
     * @param integer $findID
     * @return array
     */
    public function getSolrData($findID) {
        $findsdata = $this->getAdapter();
        $select = $findsdata->select()
                ->from($this->_name,
                        array(
                            'findIdentifier' => 'CONCAT("finds-",finds.id)',
                            'id',
                            'old_findID',
                            'objecttype',
                            'broadperiod',
                            'description',
                            'notes',
                            'inscription',
                            'classification',
                            'periodFrom' => 'objdate1period',
                            'periodTo' => 'objdate2period',
                            'fromsubperiod' => 'objdate1subperiod',
                            'tosubperiod' => 'objdate2subperiod',
                            'fromdate' => 'numdate1',
                            'todate' => 'numdate2',
                            'treasure',
                            'rally',
                            'hoard',
                            'hID' => 'hoardID',
                            'rallyID',
                            'TID' => 'treasureID',
                            'note' => 'findofnote',
                            'workflow' => 'secwfstage',
                            'institution',
                            'datefound1',
                            'datefound2',
                            'subClassification' => 'subclass',
                            'smrRef' => 'smr_ref',
                            'otherRef' => 'other_ref',
                            'musaccno',
                            'currentLocation' => 'curr_loc',
                            'created',
                            'updated',
                            'weight',
                            'height',
                            'secuid',
                            'diameter',
                            'thickness',
                            'width',
                            'length',
                            'quantity',
                            'material' => 'material1',
                            'secondaryMaterial' => 'material2',
                            'subsequentAction' => 'subs_action',
                            'completeness',
                            'decstyle',
                            'manufacture' => 'manmethod',
                            'surface' => 'surftreat',
                            'preservation',
                            'discovery' => 'discmethod',
                            'culture',
                            'finderID',
                            'recorderID',
                            'identifierID' => 'identifier1ID',
                            'createdBy'
                            ))
                ->joinLeft('findspots','finds.secuid = findspots.findID',
                        array(
                            'regionID',
                            'countyID',
                            'parishID',
                            'districtID',
                            'county',
                            'district',
                            'parish',
                            'knownas',
                            'fourFigure',
                            'gridref',
                            'latitude' => 'declat',
                            'longitude' => 'declong',
                            'fourFigureLat',
                            'fourFigureLon',
                            'geonamesID',
                            'elevation',
                            'woeid',
                            'easting',
                            'northing',
                            'coordinates' => 'CONCAT(declat,",",declong)',
                            'precision' => 'gridlen',
                            'geohash',
                            'Findspotcode' => 'old_findspotID'
                            ))
                ->joinLeft('coins', 'finds.secuid = coins.findID',
                        array(
                            'geographyID',
                            'ruler' => 'ruler_id',
                            'mint' => 'mint_id',
                            'denomination',
                            'type' => 'typeID',
                            'category' => 'categoryID',
                            'obverseDescription' => 'obverse_description',
                            'obverseLegend' => 'obverse_inscription',
                            'reverseDescription' => 'reverse_description',
                            'reverseLegend' => 'reverse_inscription',
                            'reeceID',
                            'tribe',
                            'cciNumber',
                            'mintmark' => 'reverse_mintmark',
                            'allenType' => 'allen_type',
                            'mackType' => 'mack_type',
                            'abcType' => 'rudd_type',
                            'vaType' => 'va_type',
                            'moneyer',
                            'axis' => 'die_axis_measurement'
                            ))
                ->joinLeft('mints','mints.id = coins.mint_ID',
                        array (
                            'mintName' => 'mint_name',
                            'pleiadesID',
                            'nomismaMintID' => 'nomismaID',
                            'mintWoeid' => 'woeid',
                            'mintBM' => 'bmID'
                            ))
                ->joinLeft('denominations','coins.denomination = denominations.id',
                        array(
                            'denominationName' => 'denomination'
                            ))
                ->joinLeft('rulers','coins.ruler_id = rulers.id',
                        array(
                            'rulerName' => 'issuer',
                            'rulerNomisma' => 'nomismaID',
                            'rulerDbpedia' => 'dbpedia',
                            'rulerBM' => 'bmID',
                            'rulerViaf' => 'viaf'
                            ))
                ->joinLeft('users','users.id = finds.createdBy',
                        array(
                            'creator' => 'CONCAT(users.first_name," ",users.last_name)'
                            ))
                ->joinLeft(array('users2' => 'users'),'users2.id = finds.updatedBy',
                        array('updatedBy' => 'fullname'))
                ->joinLeft(array('mat' =>'materials'),'finds.material1 = mat.id',
                        array(
                            'materialTerm' =>'term',
                            'primaryMaterialBM' => 'bmID'
                            ))
                ->joinLeft(array('mat2' =>'materials'),'finds.material2 = mat2.id',
                        array(
                            'secondaryMaterialTerm' => 'term',
                            'secondaryMaterialBM' => 'bmID'
                            ))
                ->joinLeft('decstyles','finds.decstyle = decstyles.id',
                        array('decstyleTerm' => 'term'))
                ->joinLeft('manufactures','finds.manmethod = manufactures.id',
                        array('manufactureTerm' => 'term'))
                ->joinLeft('surftreatments','finds.surftreat = surftreatments.id',
                        array('treatment' => 'term'))
                ->joinLeft('completeness','finds.completeness = completeness.id',
                        array('completenessTerm' => 'term'))
                ->joinLeft('preservations','finds.preservation = preservations.id',
                        array('preservationTerm' => 'term'))
                ->joinLeft('periods','finds.objdate1period = periods.id',
                        array('periodFromName' => 'term', 'periodFromBM' => 'bmID'))
                ->joinLeft(array('sub1' => 'subperiods'),
                        'finds.objdate1subperiod = sub1.id',
                        array('subperiodFrom' => 'term'))
                ->joinLeft(array('sub2' => 'subperiods'),
                        'finds.objdate2subperiod = sub2.id',
                        array('subperiodTo' => 'term'))
                ->joinLeft(array('p' => 'periods'),'finds.objdate2period = p.id',
                        array(
                            'periodToName' => 'term',
                            'periodToBM' => 'bmID'
                            ))
                ->joinLeft(array('p2' => 'periods'),'finds.broadperiod = p2.term',
                        array('broadperiodBM' => 'bmID'))
                ->joinLeft('cultures','finds.culture = cultures.id',
                        array('cultureName' => 'term', 'bmCultureID'))
                ->joinLeft('discmethods','discmethods.id = finds.discmethod',
                        array('discoveryMethod' => 'method'))
                ->joinLeft('subsequentActions','finds.subs_action = subsequentActions.id',
                        array('subsequentActionTerm' => 'action'))
                ->joinLeft('finds_images','finds.secuid = finds_images.find_id',
                        array())
                ->joinLeft('slides','slides.secuid = finds_images.image_id',
                        array('filename','thumbnail' => 'imageID'))
                ->joinLeft(array('users3' => 'users'), 'users3.id = slides.createdBy',
                        array('imagedir'))
                ->joinLeft('rallies','finds.rallyID = rallies.id',
                        array('rallyName' => 'rally_name'))
                ->joinLeft('ironagetribes','coins.tribe = ironagetribes.id',
                        array('tribeName' => 'tribe', 'bmTribeID'))
                ->joinLeft('medievalcategories','medievalcategories.id = coins.categoryID',
                        array('categoryTerm' => 'category'))
                ->joinLeft('medievaltypes','medievaltypes.id = coins.typeID',
                        array('typeTerm' => 'type'))
                ->joinLeft('geographyironage','geographyironage.id = coins.geographyID',
                        array('geography' => 'CONCAT(geographyironage.region,",",area)'))
                ->joinLeft('moneyers','coins.moneyer = moneyers.id',
                        array('moneyerName' => 'name', 'moneyerViaf' => 'viaf', 'moneyerBM' => 'bmID'))
                ->joinLeft('regions','findspots.regionID = regions.id',
                        array('regionName' => 'region'))
                ->joinLeft('hoards','hoards.id = finds.hoardID',
                        array('hoardName' => 'term'))
                ->joinLeft('people', 'finds.finderID = people.secuid',
                        array('finder' => 'fullname'))
                ->joinLeft(array('recorder' => 'people'),
                        'finds.recorderID =recorder.secuid',
                        array('recorder' => 'fullname'))
                ->where('finds.id = ?', (int)$findID)
                ->group('finds.id')
                ->limit(1);
        $select->setIntegrityCheck(false);
        return $findsdata->fetchAll($select);
    }
}