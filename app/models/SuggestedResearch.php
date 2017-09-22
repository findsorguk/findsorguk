<?php
/**
 * Data model for accessing suggested topics from database
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $projects = new SuggestedResearch();
 * $this->view->suggested = $projects->getAll($this->getAllParams(),0);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 October 2010, 17:12:34
 * @example /app/modules/admin/controllers/ResearchController.php
 */
class SuggestedResearch extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'suggestedResearch';


    /** Get a list of va types paginated
     * @access public
     * @param array $params The url parameters
     * @param integer $taken Whether taken or not
     * @return \Zend_Paginator
     */
    public function getAll($params, $taken){
        $topics = $this->getAdapter();
	$select = $topics->select()
                ->from($this->_name, array(
                    'id', 'title', 'description',
                    'created', 'updated'
                    ))
		->joinLeft('projecttypes',$this->_name
                        . '.level = projecttypes.id', array('type' => 'title'))
		->joinLeft('users','users.id = ' . $this->_name
                        .  '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('periods','periods.id = ' . $this->_name
                        . '.period', array('temporal' => 'term'))
		->where('taken = ?', (int)$taken);
	$paginator = Zend_Paginator::factory($select);
	if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
	}
	$paginator->setItemCountPerPage(20)->setPageRange(10);
	return $paginator;
    }

    /** Get a topic by id number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getTopic($id){
	$topics = $this->getAdapter();
	$select = $topics->select()
                ->from($this->_name, array(
                    'id', 'title', 'description',
                    'created', 'updated'
                    ))
		->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('periods','periods.id = ' . $this->_name
                        . '.period', array('temporal' => 'term'))
		->where($this->_name . '.id = ?', (int)$id);
	return $topics->fetchAll($select);
    }

    /** Get a topic by type
     * @access public
     * @param integer $type
     * @return array
     */
    public function getTopicByType($type){
	$topics = $this->getAdapter();
	$select = $topics->select()
		->from($this->_name, array(
                    'id', 'title', 'description',
                    'created', 'updated'
                    ))
		->joinLeft('projecttypes',$this->_name
                        . '.level = projecttypes.id', array('type' => 'title'))
		->joinLeft('users','users.id = '. $this->_name
                        . '.createdBy', array('fullname'))
		->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'))
		->joinLeft('periods','periods.id = ' . $this->_name
                        . '.period',array('temporal' => 'term'))
		->where($this->_name . '.level = ?', (int)$type);
	return $topics->fetchAll($select);
    }
}