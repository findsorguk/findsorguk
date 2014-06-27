<?php
/** 
 * A model for pulling decorative styles from the database
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $decorations = new DecStyles();
 * $decoration_options = $decorations->getStyles();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license |GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/forms/AdvancedSearchForm.php
*/

class DecStyles extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'decstyles';
	
    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';

    /** Retrieve a list of decoration styles as a key pair value chain
    * @return array
    */
    public function getStyles() {
        $key = md5('decstyledd');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->order('term');
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }

    /** Retrieve an array of decoration style by term id
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDecStyle($id){
        $select = $this->select()
                ->from($this->_name, array('term'))
                ->where('id = ?',(int) $id);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve a list of decoration styles (all columns)
     * @access public
     * @return array
     */
    public function getDecStyles(){
        $styles = $this->getAdapter();
        $select = $styles->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1);
        return $styles->fetchAll($select);
    }

    /** Retrieve an individual decoration style (all columns)
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDecStyleDetails($id) {
        $select = $this->select()
                ->from($this->_name)
                ->where('valid = ?',(int)1)
                ->where('id = ?',(int)$id);
        return $this->getAdapter()->fetchAll($select);
    }

    /** Retrieve an individual decoration style count by objects - expensive
     * @access public
     * @param integer $id
     * @return array
     */
    public function getDecStylesCounts($id) {
    $styles = $this->getAdapter();
    $select = $styles->select()
            ->from($this->_name)
            ->joinLeft('finds','finds.decstyle = ' . $this->_name . '.id', 
                    array('c' => 'count(finds.id)'))
            ->where('valid = ?',(int)1)
            ->where($this->_name . '.id = ?',(int)$id)
            ->group($this->_name . '.id');
    return $styles->fetchAll($select);
    }

    /** Retrieve a list of decorative styles for admin interface
     * @access public
     * @return array
     */
    public function getDecStylesAdmin() {
        $styles = $this->getAdapter();
        $select = $styles->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name . '.createdBy', 
                        array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name . '.updatedBy',
                        array('fn' => 'fullname'));
        return $styles->fetchAll($select);
    }
}