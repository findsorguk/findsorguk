<?php
/** Model for interacting with publication types
 *
 * An example of code use:
 *
 * <code>
 * <?pho
 * $types = new PublicationTypes();
 * $type_options = $types->getTypes();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/forms/PublicationForm.php
 */
class PublicationTypes extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'publicationtypes';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Get dropdown list of publication types
     * @access public
     * @return array
     */
    public function getTypes() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('term ASC');
        return $this->getAdapter()->fetchPairs($select);
    }
}
