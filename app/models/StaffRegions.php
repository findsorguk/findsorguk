<?php
/**
 * Model for interacting with staff regions table
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new StaffRegions();
 * $data = $model->getOptions();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
  * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @todo add edit and delete functions, cache
 * @version 1
 * @example /app/forms/ContactForm.php
 */
class StaffRegions extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'staffregions';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get a dropdown key value pair list for staff regions
     * @access public
     * @return array
     */
    public function getOptions() {
        $select = $this->select()
                ->from($this->_name, array('ID', 'description'))
                ->order('description ASC');
        return $this->getAdapter()->fetchPairs($select);
    }
}