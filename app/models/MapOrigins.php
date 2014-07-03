<?php
/** Model for origins of map grid references
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new MapOrigins();
 * $data = $model->getOrigins();
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
 * @todo add edit and delete functions
 * @example /app/forms/FindSpotForm.php
 */
class MapOrigins extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'maporigins';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve all map origins
     * @access public
     * @return array
     */
    public function getOrigins() {
        $origins = $this->getAdapter();
        $select = $origins->select()
                >from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy', array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy', array('fn' => 'fullname'));
        return $origins->fetchAll($select);
    }

    /** Retrieve all map origins as key to value pairs for dropdown listing
     * @access public
     * @return array
     */
    public function getValidOrigins() {
        $origins = $this->getAdapter();
        $select = $origins->select()
                ->from($this->_name, array('id', 'term'));
        return $origins->fetchPairs($select);
    }
}