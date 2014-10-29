<?php

/** Access, manipulate and delete coin summary data.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new CoinSummary();
 * $data = $model->getCoinSummary($id);
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
class CoinSummary extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'coinsummary';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get coin summary for a hoard record
    * @access public
    * @param integer $hoardId
    * @return array
    * @todo Make this work properly for all periods of coinage
    */
    public function getCoinSummary($hoardId){
        $select = $this->select()
            ->from($this->_name, array(
                'summaryID' => 'id',
                'hoardID',
                'broadperiod',
                'denomination',
                'geographyID',
                'ruler_id',
                'mint_id',
                'numdate1',
                'numdate2',
                'quantity',
                'createdBy',
                'institution'
            ))
            ->joinLeft('hoards','coinsummary.hoardID = hoards.secuid',
                array(
                    'id'))
            ->joinLeft('denominations','coinsummary.denomination = denominations.id',
                array(
                'denomination'))
            ->joinLeft('rulers','coinsummary.ruler_id = rulers.id',
                array(
                'ruler' => 'issuer'))
            ->joinLeft('mints','coinsummary.mint_id = mints.id',
                array(
                'mint' => 'mint_name'))
            ->joinLeft('geographyironage','coinsummary.geographyID = geographyironage.id',
                array(
                    'geographicarea' => 'region'))
            ->where('hoards.id = ?', (int)$hoardId);
            $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchAll($select);

    }

    public function add( $data )
    {
        if(empty($data['created'])){
            $data['created'] = $this->timeCreation();
        }
        if(empty($data['createdBy'])){
            $data['createdBy'] = $this->getUserNumber();
        }

        if(empty($data['secuid'])) {
            $data['secuid'] = $this->generateSecuId();
        }
        foreach($data as $k => $v) {
            if ( $v == "") {
                $data[$k] = NULL;
            }
        }
        return parent::insert( $data );
    }

    /** A function for getting data for updating coin summary solr index
     * IMPORTANT: If you change this, you must change the solr config files too.
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSolrData( $id )
    {
        $slides = $this->getAdapter();
        $select = $slides->select()
            ->from($this->_name,array(
                'summaryIdentifier' => 'CONCAT("coinsummary-",coinsummary.id)',
                'id' => 'coinsummary.id',
                'broadperiod',
                'fromDate' => 'numdate1',
                'toDate' => 'numdate2',
                'denominationID' => 'denomination',
                'mintID' => 'mint_id',
                'rulerID' => 'ruler_id',
                'quantity',
                'geographyID',
                'updatedBy',
                'createdBy',
                'updated',
                'created'
            ))
            ->joinLeft('hoards', 'coinsummary.hoardID = hoards.secuid', array('hoardID' => 'id', 'hoard' => 'hoardID'))
            ->joinLeft('denominations', 'coinsummary.denomination = denominations.id', array('denomination'))
            ->joinLeft('mints', 'coinsummary.mint_id = mints.id', array('mint' => 'mint_name'))
            ->joinLeft('geographyironage', 'coinsummary.geographyID = geographyironage.id',
                array('geography' => 'CONCAT(region, " " , area, " " , tribe)'))
            ->joinLeft('rulers', 'coinsummary.ruler_id = rulers.id', array('ruler' => 'issuer'))
            ->joinLeft('users', 'coinsummary.createdBy = users.id', array('creator' => 'fullname'))
            ->joinLeft(array('users2' => 'users'), 'coinsummary.updatedBy = users2.id', array('updater' => 'fullname'))
            ->where('coinsummary.id = ?',(int)$id);
        return $slides->fetchAll($select);
    }
}