<?php
/** Retrieve and manipulate data for open calais tagged content
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $model = new OpenCalaisModel();
 * $data = $model->getGeoTagCloud();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/database/controllers/TagsController.php 
 * @todo Generate tag searching via a solr index instead.
 */
class OpenCalaisModel extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'opencalais';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';
	
    /** The higher level array
     * @access protected
     * @var array
     */
    protected $higherlevel = array('admin','flos','fa','treasure');

    /** The restricted array
     * @access protected
     * @var array
     */
    protected $restricted = array('public','member','research','hero');

    /** The edit test array
     * @access protected
     * @var array
     */
    protected $edittest = array('flos','member');

    /** Get some tagged content
     * @access public
     * @param integer $id
     * @param string $type
     * @return array
     */
    public function getTaggedContent($id, $type){
        $tags = $this->getAdapter();
        $select = $tags->select()
                ->from($this->_name)
                ->where('contentID = ?' , (int)$id)
                ->where('origin != ?', (string)'YahooGeo')
                ->where('contenttype = ?',( string)$type);
        return $tags->fetchAll($select);
    }

    /** Get geotags
     * @access public
     * @param integer $id
     * @param string $type
     * @return array
     */
    public function getGeoTags($id, $type){
    $tags = $this->getAdapter();
    $select = $tags->select()
            ->from($this->_name)
            ->where('contentID = ?' , (int)$id)
            ->where('contenttype = ?', (string)$type)
            ->where('origin = ?', (string)'YahooGeo');
    return $tags->fetchAll($select);
    }

    /** Get some tagged content by particular tag
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getRecordsByTag(array $params) {
        $tags = $this->getAdapter();
        $select = $tags->select()
                ->from($this->_name,array('term'))
                ->joinLeft('finds',$this->_name . '.contentID = finds.id', 
                        array(
                            'id', 'old_findID','objecttype',
                            'broadperiod','description'
                            ))
                ->joinLeft('findspots','finds.secuid = findspots.findID',
                        array('county'))
                ->joinLeft('finds_images','finds.secuid = finds_images.find_id',
                        array())
                ->joinLeft('slides','slides.secuid = finds_images.image_id',
                        array('i' => 'imageID','f' => 'filename'))
                ->joinLeft('users','users.id = finds.createdBy',
                        array('username','fullname','institution'))
                ->where('term = ?' , (string)$params['tag'])
                ->where('origin != ?', (string)'YahooGeo')
                ->where('contenttype = ?','findsrecord')
                ->group('finds.id');
        if(in_array($this->getUserRole(), $this->restricted)){
            $select->where('finds.secwfstage NOT IN ( 1, 2 )');
        }
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;
    }

    /** Get some geotagged content by particular tag
     * @access public
     * @param array $params
     * @return \Zend_Paginator
     */
    public function getRecordsByGeoTag(array $params) {
        $tags = $this->getAdapter();
        $select = $tags->select()
                ->from($this->_name,array('term'))
                ->joinLeft('finds',$this->_name . '.contentID = finds.id',
                        array(
                            'id', 'old_findID', 'objecttype', 
                            'broadperiod', 'description'
                            ))
                ->joinLeft('findspots','finds.secuid = findspots.findID', 
                        array('county'))
                ->joinLeft('finds_images','finds.secuid = finds_images.find_id', 
                        array())
                ->joinLeft('users','users.id = finds.createdBy',
                        array('username','fullname','institution'))
                ->joinLeft('slides','slides.secuid = finds_images.image_id',
                        array('i' => 'imageID','f' => 'filename'))
                ->where('term = ?' ,(string)$params['tag'])
                ->where('origin = ?', (string)'YahooGeo')
                ->where('contenttype = ?','findsrecord')
                ->group('finds.id');
        if(in_array($this->getUserRole(), $this->restricted)) {
            $select->where('finds.secwfstage NOT IN ( 1, 2 )');
        }
        $paginator = Zend_Paginator::factory($select);
        Zend_Paginator::setCache($this->_cache);
        $paginator->setItemCountPerPage(30)->setPageRange(10);
        if(isset($params['page']) && ($params['page'] != ""))  {
            $paginator->setCurrentPageNumber((int)$params['page']);
        }
        return $paginator;
    }

    /** Get some tags for a cloud
     * @access public
     * @return array
     */
    public function getTagsCloud() {
        $tags = $this->getAdapter();
        $select = $tags->select()
                ->from($this->_name,array('total' => 'COUNT(*)','term'))
                ->joinLeft('finds',$this->_name . '.contentID = finds.id',
                        array())
                ->where('contenttype = ?', (string)'findsrecord')
                ->where('origin != ?', (string)'YahooGeo')
                ->group('term');
        return $tags->fetchAll($select);
    }

    /** Get some tags for a cloud front page
     * @access public
     * @return array
     */
    public function getTagsCloudFront() {
        $key = md5('tagsfront' . $this->getUserRole());
        if (!$tags = $this->_cache->load($key)){
            $tags = $this->getAdapter();
            $select = $tags->select()
                    ->from($this->_name,array('total' => 'COUNT(*)','term'))
                    ->joinLeft('finds',$this->_name . '.contentID = finds.id',
                            array())
                    ->where('contenttype = ?', (string)'findsrecord')
                    ->where('origin != ?', (string)'YahooGeo')
                    ->order('total DESC')
                    ->group('term')
                    ->limit(25);
            $data = $tags->fetchAll($select);
            $this->_cache->save($data, $key);
        }
        return $tags;
    }
    
    /** Get some tags for a cloud of geo
     * @access public
     * @return array
     */
    public function getGeoTagCloud() {
        $tags = $this->getAdapter();
        $select = $tags->select()
                ->from($this->_name, 
                        array('total' => 'COUNT(*)', 'term'))
                ->joinLeft('finds',$this->_name 
                        . '.contentID = finds.id', array())
                ->where('contenttype = ?', (string)'findsrecord')
                ->where('origin = ?', (string)'YahooGeo')
                ->group('term')
                ->order('total DESC');
        if(in_array($this->getUserRole(), $this->restricted)) {
            $select->where('finds.secwfstage NOT IN ( 1, 2 )');
        }
        return $tags->fetchAll($select);
    }
}