<?php
/** Model for manipulating completeness details
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Completeness();
 * $data = $model->getDetails($id);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 2
 * @since 22 September 2011
 * @todo add some caching to model
 * @example /app/modules/datalabs/controllers/TerminologyController.php
*/
class Completeness extends Pas_Db_Table_Abstract {

    /** The table name
     * @access public
     * @var string table name
     */
    protected $_name = 'completeness';

    /** The table primary key
     * @access public
     * @var int The key
     */
    protected $_primary = 'id';

    /** Get completeness details by id
    * @param integer $id The id to query by
    * @return array The details for the id number
    * @todo add caching
    */
    public function getDetails($id) {
        $comp = $this->getAdapter();
        $select = $comp->select()
                ->from($this->_name)
                ->where('id = ?', (int)$id)
                ->order('id');
        return $comp->fetchAll($select);
    }
}