<?php
/** Model for manipulating data quality ratings
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new DataQuality();
 * $data = $model->getRatings();
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
@license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version  1
 * @since  15 August 2014
 * @example /app/forms/HoardForm.php
 */

class DataQuality extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'dataquality';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get all terminal coin dating reasons where valid as key value pairs
     * @access public
     * @return array
     */
    public function getRatings() {
        $select = $this->select()
            ->from($this->_name, array('id', 'rating'))
            ->order('id')
            ->where('valid = ?',(int)1);
        return $this->getAdapter()->fetchPairs($select);
    }

}
