<?php
/**
 * Get a list of date qualifiers for numeric dating
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new DateQualifiers();
 * $data = $model->getTerms();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/forms/FindForm.php
*/
class DateQualifiers extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'datequalifiers';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primaryKey = 'id';

    /** Get qualifier terms for form listing as key value pairs
     * @access public
     * @return array
     */
    public function getTerms(){
        $key = md5('circa');
        if (!$options = $this->_cache->load($key)) {
            $select = $this->select()
                    ->from($this->_name, array('id', 'term'))
                    ->order($this->_primaryKey)
                    ->where('valid = ?',(int)1);
            $options = $this->getAdapter()->fetchPairs($select);
            $this->_cache->save($options, $key);
        }
        return $options;
    }
}