<?php
/** A model for pulling denominations from the database
* @category Pas
* @package Pas_Db_Table
* @subpackage Abstract
* @author Daniel Pett dpett @ britishmuseum.org
* @copyright 2010 - DEJ Pett
* @license 		GNU General Public License
* @version 		1
* @since 		22 September 2011
* @todo add caching and amalgamate some functions (duplication!!!)
*/
class Denominations extends Pas_Db_Table_Abstract {

	protected $_name = 'denominations';

	protected $_primary = 'id';


	/** Get denomination by period as a list
	* @param $period
	* @return array
	*/
	public function getDenByPeriod($period) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name)
		->where($this->_name . '.valid = ?', (int)1)
		->where($this->_name . '.period = ?', (int)$period)
		->order('denomination');
	return $denoms->fetchAll($select);
	}

	/** retrieve a key pair list of roman denominations
	 */
	public function getOptionsRoman() {
	$select = $this->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?',(int)21)
		->order('denomination');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get a list of Iron Age denominations as key pairs for dropdowns
     */
	public function getOptionsIronAge() {
	$select = $this->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?', (int)16)
		->order('denomination');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get a list of Early medieval denominations as key pairs for dropdowns
     */
	public function getOptionsEarlyMedieval() {
	$select = $this->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?', (int)47)
		->order('denomination');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

	/** Get a list of Medieval denominations as key pair values
	*/
	public function getOptionsMedieval() {
	$select = $this->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?', (int)29)
		->order('denomination');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get a list of post medieval denominations as key pair values
    */
	public function getOptionsPostMedieval() {
	$select = $this->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?', (int)36)
		->where('valid = ?', (int)1)
		->order('denomination');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get a list of Greek denominations
    */
	public function getDenomsGreek() {
	$select = $this->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?',(int)66)
		->where('valid = ?',(int)1)
		->order('denomination');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
    }

    /** Get a list of Byzantine denominations as key pair values
	*/
	public function getDenomsByzantine() {
	$select = $this->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?', (int)67)
		->where('valid = ?', (int)1)
		->order('denomination');
        $options = $this->getAdapter()->fetchPairs($select);
        return $options;
    }

    /** Get a list of Roman rulers and denominations
     *
     * @param integer $ruler
     */
	public function getRomanRulerDenom($ruler) {
	$select = $this->select()
		->from($this->_name, array('id', 'term' => 'denomination'))
		->joinLeft('denominations_rulers', 'denominations.id = denominations_rulers.denomination_id',
		array())
		->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id', array())
		->where('denominations_rulers.ruler_id= ?',$ruler);
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Get an admin list of rulers to denominations
	 *
	 * @param integer $ruler
	 */
	public function getRomanRulerDenomAdmin($ruler) {
	$options = $this->getAdapter();
	$select = $options->select()
		->from($this->_name, array('id', 'term' => 'denomination'))
 		->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
 		array('created', 'linkid' => 'id'))
		->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id', array())
		->joinLeft('users','users.id = denominations_rulers.createdBy', array('fullname'))
		->joinLeft('periods','periods.id = denominations.period', array('period' => 'term'))
		->where('denominations_rulers.ruler_id= ?', (int)$ruler)
		->order('denomination');
	return $options->fetchAll($select);
	}

	/** Get a list of early medieval rulers to denominations
	 *
	 * @param integer $ruler
	 */
	public function getEarlyMedRulerDenom($ruler) {
	$select = $this->select()
		->from($this->_name, array('id', 'term' => 'denomination'))
		->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
		array())
		->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
		->where('denominations_rulers.ruler_id= ?', (int)$ruler)
		->group('denomination');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Get a list of early medieval rulers and their denominations
	 *
	 * @param integer $ruler
	 */
	public function getEarlyMedRulerToDenomination($ruler) {
	$select = $this->select()
		->from($this->_name, array('id',  'denomination'))
		->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
		array())
		->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array())
		->where('denominations_rulers.ruler_id= ?',(int)$ruler)
		->group('denomination');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Get a key value pair list of early medieval rulers and denominations
	 *
	 * @param integer $ruler
	 */
	public function getEarlyMedRulerToDenominationPairs($ruler) {
	$select = $this->select()
		->from($this->_name, array('id',  'denomination'))
		->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
		array())
		->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',
		array())
		->where('denominations_rulers.ruler_id= ?',(int)$ruler)
		->group('denomination');
	$options = $this->getAdapter()->fetchPairs($select);
	return $options;
	}

	/** Get a list of Post Medieval rulers to denominations
	 *
	 * @param integer $ruler
	 */
	public function getPostMedRulerDenom($ruler) {
	$select = $this->select()
		->from($this->_name, array('id', 'term' => 'denomination'))
		->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id', array())
		->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id', array())
		->where('denominations_rulers.ruler_id= ?', (int)$ruler)
		->group('denomination');
	$options = $this->getAdapter()->fetchAll($select);
	return $options;
	}

	/** Get a list of Iron Age denominations
	*/
	public function getIronAgeDenoms() {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?', (int)16)
		->where('valid = ?', 1)
		->order('id');
	return $denoms->fetchAll($select);
    }

    /** Get a list of Iron Age denoms
     * @todo does this need deleting, see prior function!
     */
	public function getIronAgeDenom() {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name, array('id', 'denomination'))
		->where('period = ?', (int)16)
		->order('id');
	return $denoms->fetchAll($select);
    }

    /** Get a denomination name from its ID number#
     *
     * @param integer $denomination
     */
	public function getDenomName($denomination) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name, array('id', 'denomination'))
		->where('id = ?', (int)$denomination)
		->group('id')
		->order('id');
	return $denoms->fetchAll($select);
    }

    /** Get am emperor's denominations
     *
     * @param integer $id
     */
	public function getEmperorDenom($id) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name)
		->joinLeft('coins_denomxruler','coins_denomxruler.denomID = denominations.id', array())
		->joinLeft('rulers','coins_denomxruler.rulerID = rulers.id', array('rulerID' => 'id', 'issuer'))
		->joinLeft('emperors','rulers.id = emperors.pasID', array())
		->where('rulers.id = emperors.pasID')
		->where('emperors.id = ?', (integer)$id)
		->order('emperors.date_from');
	return $denoms->fetchAll($select);
	}

	/** Get a denomination by period and id number
	 *
	 * @param integer $id
	 * @param integer $period
	 */
	public function getDenom($id, $period){
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name)
		->joinLeft('materials',$this->_name . '.material = materials.id',array('term'))
		->joinLeft('coins',$this->_name . '.id = coins.denomination',array())
		->where($this->_name . '.id = ?', $id)
		->where('period = ?',$period)
		->group($this->_primary);
	return $denoms->fetchAll($select);
    }

    /** Get denominations by period and paginated by page
     *
     * @param $period
     * @param $page
     */
	public function getDenominations($period, $page) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'))
		->where('period = ?',(int)$period)
		->order('denomination');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($page) && ($page != "")) {
	$paginator->setCurrentPageNumber($page);
	}
	return $paginator;
	}

	/** Get denominations for json by period
	 *
	 * @param integer $period
	 */
	public function getDenominationsJson($period) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name)
		->joinLeft('users','users.id = ' . $this->_name . '.createdBy',array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',array('fn' => 'fullname'))
		->where('period = ?', (int)$period)
		->order('denomination');
	return $denoms->fetchAll($select);
	}

	/** Get a pair list of all denominations by period
	 *
	 * @param integer $period
	 */
	public function getDenomsAdd($period){
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name,array('id', 'denomination'))
		->where('period = ?', (int)$period)
		->order('denomination');
	return $denoms->fetchPairs($select);
    }

    /** get a paginated list of valid denominations
     *
     * @param array $params
     */
	public function getDenomsValid( array $params) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name)
		->joinLeft('materials','denominations.material = materials.id', array('mat' => 'term'))
		->joinLeft('periods','periods.id = denominations.period', array('temporal' => 'term'))
		->where($this->_name . '.valid = ?',(int)1)
		->order('denomination');
	$paginator = Zend_Paginator::factory($select);
	$paginator->setItemCountPerPage(30)
		->setPageRange(10);
	if(isset($params['page']) && ($params['page'] != "")) {
	$paginator->setCurrentPageNumber($params['page']);
	}
	return $paginator;
    }

    /** Fer a ruler to denomination by id
     *
     * @param integer $id
     */
	public function getRulerDenomination( $id) {
	$options = $this->getAdapter();
	$select = $options->select()
		->from($this->_name, array('id','denomination'))
		->joinLeft('denominations_rulers','denominations.id = denominations_rulers.denomination_id',
		array())
		->joinLeft('rulers','rulers.id = denominations_rulers.ruler_id',array('i'=>'rulers.id', 'issuer'))
		->where('denominations_rulers.denomination_id= ?',(int)$id)
		->group('issuer');
	return $options->fetchAll($select);
	}

	/** Get a denomination by ID
	 *
	 * @param integer $id
	 */
	public function getDenomination($id) {
	$options = $this->getAdapter();
	$select = $options->select()
		->from('denominations',array('denomination','id'))
		->joinLeft('materials','denominations.material = materials.id',array('mat' => 'term'))
		->joinLeft('periods','periods.id = denominations.period',array('temporal' => 'term'))
		->where('denominations.id =' . $id);
	return $options->fetchAll($select);
	}

	/** Get a list of denominations for the sitemap by period
	 *
	 * @param integer $period
	 */
	public function getDenominationsSitemap($period) {
	if (!$data = $this->_cache->load('denomsSiteMap' . $period)) {
	$denoms = $this->getAdapter();
	$select = $denoms->select()
		->from($this->_name,array('id','denomination','updated'))
		->where($this->_name . '.valid = ?', (int)1)
		->where($this->_name . '.period = ?',(int)$period)
		->order('denomination');
	$data = $denoms->fetchAll($select);
	$this->_cache->save($data, 'denomsSiteMap' . $period);
	}
	return $data;
	}
}
