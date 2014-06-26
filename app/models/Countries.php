<?php
/**
 * A model for retrieving a list of ISO countries
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new Countries();
 * $data = $model->getOptions();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license GNU General Public License
 * @version 1
 * @since 22 September 2011
 * @todo add caching
 * @example /app/forms/ContactForm.php
*/

class Countries extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'countries';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'iso';

    /** retrieve a key pair list of ISO countries
     * @access public
     * @return array
     * @todo Add caching
     */
    public function getOptions() {
        $select = $this->select()
                ->from($this->_name, array('iso', 'printable_name'))
                ->order('printable_name ASC');
	return $this->getAdapter()->fetchPairs($select);
    }
}
