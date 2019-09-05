<?php
/** A model for manipulating the bibliograpic data;
 * Books are stored in publications table
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Bibliography();
 * $data = $model->fetchFindBook($id);
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @copyright 2010 - DEJ Pett
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1.0
 * @since 22 September 2011
 * @example /app/modules/database/controllers/ReferencesController.php
*/

class Bibliography extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'bibliography';

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The string used to generate cache id
     * for the function getPersonDetails()
     */
    const CACHE_ID = 'bibliobook';

    /** Get cached data for a book
     * @access public
     * @param integer $id
     * @return array
     */
    public function fetchFindBook($id, $table = 'finds'){
        if($table == 'artefacts') {
            $useTable = 'finds';
        } else {
            $useTable = $table;
        }
        $rows = $this->_cache->load('bibliobook' . (int)$id . $table);
    	if (!$rows ) {
        $refs = $this->getAdapter();
        $select = $refs->select()
                ->from($this->_name, array('pages_plates','reference','pubID'))
                ->joinLeft('publications','publications.secuid = bibliography.pubID',
                        array('publicationtitle' => 'title', 'authors'))
                ->joinLeft(array('recordtable' => $useTable),'recordtable.secuid = bibliography.findID', array('id'))
                ->where($this->_name . '.id = ?', $id);
            $rows = $refs->fetchAll($select);
            $rows[0]['controller'] = $table;
    	$this->_cache->save($rows, 'bibliobook' . (int)$id . $table);
    	}
        return $rows;
    }

    public function getReferenceByfindID($findID)
    {
	$refs = $this->getAdapter();
        $select = $refs->select()
                ->from($this->_name, array(
		    'id', 'old_publicationID', 'findID', 'pages_plates',
		    'reference','pubID', 'vol_no', 'created', 'updated',
		    'createdBy', 'updatedBy', 'secuid'))
		->where('findID = ?', (string)$findID);
        return $refs->fetchAll($select);
    }

    /** Get the last reference created by the user
     * @access public
     * @param integer $userid
     * @return array
     */
    public function getLastReference($userid)
    {
	$refs = $this->getAdapter();
        $refs = $this->getAdapter();
        $select = $refs->select()
                ->from($this->_name, array('pages_plates','reference','pubID','createdBy'))
                ->joinLeft('publications','publications.secuid = bibliography.pubID',
 	                array('authors','title','publication_place','publisher'))
                ->where($this->_name . '.createdBy = ?', $userid)
                ->order('bibliography.created DESC')
                ->limit(1);
        return $refs->fetchAll($select);
    }

    // Clear the cache
    public function clearCacheEntry($id, $table)
    {
	$this->_cache->remove(self::CACHE_ID . (int)$id . $table);
    }
}
