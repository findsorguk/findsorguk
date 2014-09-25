<?php
/** Retrieve and manipulate publications data
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $authors = new Publications();
 * $authorList = $authors->getAuthors();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/forms/ReferenceFindForm.php
 */
class Publications extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'publications';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get authors
     * @access public
     * @return array
     */
    public function getAuthors() {
        $publications = $this->getAdapter();
        $select = $publications->select()
                ->from($this->_name, array( 'authors', 'authors' ))
                ->order('authors ASC')
                ->group('authors');
        return $publications->fetchPairs($select);
    }

    /** Get titles for specific author
     * @access public
     * @param string $author
     * @return array
     */
    public function getTitles( $author ) {
        $publications = $this->getAdapter();
        $select = $publications->select()
                ->from($this->_name, array( 'id' => 'secuid', 'term' => 'title' ))
                ->where('authors = ?', $author)
                ->order('title ASC')
                ->group('title');
        return $publications->fetchAll($select);
    }

    /** Get dropdown pairs list by author
     * @access public
     * @param string $author
     */
    public function getTitlesPairs( $author ) {
        $publications = $this->getAdapter();
        $select = $publications->select()
                ->from($this->_name, array( 'secuid',  'title' ))
                ->where('authors = ?', $author)
                ->order('title ASC')
                ->group('title');
        return $publications->fetchPairs($select);
    }

    /** Get the secuids
     * @access public
     * @return array
     */
    public function getSecuids() {
        $publications = $this->getAdapter();
        $select = $publications->select()
                ->from($this->_name, array( 'secuid'));
        return $publications->fetchAll($select);
    }

    /** Get all refs for a find or a hoard
    * @param integer $id find or hoard to reference
     * @param string $table
    * @return array
    */
    public function getReferences($id, $table = 'finds') {
        $refs = $this->getAdapter();
        $select = $refs->select()
                ->from($this->_name, array(
                    'authors','title','publication_year',
                    'publication_place', 'vol_no','ISBN',
                    'publisher','medium','accessedDate',
                    'url','publication_type','id',
                    'in_publication'
                    ))
                ->joinLeft('bibliography','publications.secuid = bibliography.pubID',
                        array('pp' => 'pages_plates','i' => 'id'))
                ->joinLeft(array('recordtable' => $table),'recordtable.secuid = bibliography.findID',
                        array('fID' => 'id','createdBy'))
                ->joinLeft('publicationtypes',
                        'publicationtypes.id = publications.publication_type',
                        array('term'))
                ->where('recordtable.id = ?', (int)$id)
                ->group('publications.secuid');
        return $refs->fetchAll($select);
    }


    /** Get all reference details
    * @param integer $id reference to reference
    * @return array
    */
    public function getPublicationDetails($id) {
        $refs = $this->getAdapter();
        $select = $refs->select()
                ->from($this->_name,array(
                    'id','created','updated',
                    'title','publisher','authors',
                    'ISBN','publication_year','publication_place',
                    'editors','in_publication', 'biab',
                    'doi'
                    ))
                ->joinLeft('publicationtypes',
                        'publications.publication_type = publicationtypes.id',
                        array('publicationType' => 'term'))
                ->joinLeft('users','publications.createdBy = users.id',
                        array('createdBy' => 'fullname'))
                ->joinLeft(array('users2' => 'users'),
                        'publications.updatedBy = users2.id',
                        array('updatedBy' => 'fullname'))
                ->where('publications.id = ?', (int)$id);
        return  $refs->fetchAll($select);
    }


    /** Get publication data for solr updates
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSolrData($id){
        $refs = $this->getAdapter();
        $select = $refs->select()
                ->from($this->_name,array(
                    'identifier' => 'CONCAT("publications-",publications.id)',
                    'publications.id',
                    'title',
                    'authors',
                    'editors',
                    'inPublication' => 'in_publication',
                    'isbn',
                    'placePublished' => 'publication_place',
                    'yearPublished' => 'publication_year',
                    'created',
                    'updated',
                    'publisher'
                    ))
                ->joinLeft('publicationtypes',$this->_name
                        . '.publication_type = publicationtypes.id',
                        array('pubType' => 'term'))
                ->where('publications.id = ?',(int)$id);
        return $refs->fetchAll($select);
    }
}