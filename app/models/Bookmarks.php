<?php
/** Model for pulling bookmark system data
 *
 * An example of use:
 * <code>
 * <?php
 * $model = new Bookmarks();
 * $data = $model->geValidBookmarks;
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Db_Table
 * @license  GNU General Public License
 * @version  1
 * @since    22 September 2011
 * @todo Perhaps deprecate
 */
class Bookmarks extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'bookmarks';

    /** The primary key
     * @access protected
     * @var type
     */
    protected $_primary = 'id';

    /** Get all valid bookmarks
    * @return array
    * @access public
    */
    public function getValidBookmarks() {
        if (!$data = $this->_cache->load('bookmarksSite')) {
            $bookmarks = $this->getAdapter();
            $select = $bookmarks->select()
                    ->from($this->_name, array('image','url','service'))
                    ->where('valid = ?',(int)1);
            $data =  $bookmarks->fetchAll($select);
            $this->_cache->save($data, 'bookmarksSite');
        }
        return $data;
    }
}