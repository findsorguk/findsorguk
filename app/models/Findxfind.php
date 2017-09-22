<?php
/**
 * Linked finds lookup table
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @todo This could be deprecated. Solr does the related finds donkey work
*/
class Findxfind extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'findxfind';

    /** Set up the array of restricted user accounts
     * @access protected
     * @var array $_restricted
     */
    protected $_restricted = array('public','member','research');

    /** Get linked finds
     * @access public
     * @param string $secuid
     * @return array
     * @todo add caching
     */
    public function getRelatedFinds($secuid) {
        $relatedfinds = $this->getAdapter();
        $select = $relatedfinds->select()
                ->from($this->_name, array('i' => 'id'))
                ->joinLeft(array('finds1' => 'finds'),
                        'finds1.secuid = findxfind.find1ID',array())
                ->joinLeft(array('finds2' => 'finds'),
                        'finds2.secuid = findxfind.find2ID',array(
                            'id' ,'broadperiod', 'objecttype',
                            'old_findID','secuid'
                            ))
                ->where('findxfind.find1ID = ? ', (string)$secuid) ;
        if(in_array($this->getUserRole(), $this->_restricted)) {
            $select->where('finds1.secwfstage NOT IN ( 1, 2 )');
        }
        return $relatedfinds->fetchAll($select);
    }
}