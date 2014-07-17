<?php
/** Model for interacting with webservices tabl
 *
 * An example of use:
 * <code>
 * <?php
 * $model = new WebServices();
 * $data = $model->getValidServices();
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
 * @example /app/forms/SocialAccountsForm.php
*/

class WebServices extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'webServices';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** Retrieve all web services
     * @access public
     * @return array
     */
    public function getValidServices() {
        $key = md5('webservices');
        if (!$data = $this->_cache->load($key)) {
            $webservices = $this->getAdapter();
            $select = $webservices->select()
                    ->from($this->_name,array('service','service'))
                    ->where('valid = ?',(int)1);
            $data =  $webservices->fetchPairs($select);
            $this->_cache->save($data, $key);
        }
        return $data;
    }
}