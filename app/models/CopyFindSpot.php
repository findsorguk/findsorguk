<?php
/**
 * A model to update and manipulate the copy findspot table
 *
 * An example of use:
 * <code>
 * <?php
 * $model = new CopyFindSpot();
 * $data = $model->getConfig();
 * ?>
 * </code>
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/users/controllers/ConfigurationController.php
 */

class CopyFindSpot extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var type
     */
    protected $_name = 'copyFindSpot';

    /** The primary key
     * @access protected
     * @var type
     */
    protected $_primary = 'id';

    /** The default array
     * @access protected
     * @var type
     */
    protected $_default = array(
        'county', 'district', 'parish',
        'knownas', 'regionID', 'knownas',
        'gridref', 'gridrefsrc', 'gridrefcert',
        'description', 'comments', 'landusecode',
        'landusevalue', 'depthdiscovery', 'countyID',
        'parishID', 'districtID'
        );

    /** Get the configuration
     * @access public
     * @return array
     */
    public function getConfig(){
        $copy = $this->getAdapter();
        $select = $copy->select()
                ->from($this->_name, array('fields'))
                ->where('userID = ?', (int)$this->getUserNumber());
        $fields = $copy->fetchAll($select);
        if($fields) {
            $checked = unserialize($fields['0']['fields']);
        } else {
            $checked =  $this->_default;
        }
        return $checked;
    }

    /** update the base configuration
     * @access public
     * @param array $data
     * @return int
     */
    public function updateConfig( array $data ){
        if(array_key_exists('csrf', $data)){
            unset($data['csrf']);
        }
        foreach ( $data as $key => $value){
            if(is_null($value) || $value === '' || $value === '0'){
                unset($data[$key]);
            }
        }
        $newFields = array_keys($data);
        $updateData['fields'] = serialize($newFields);
        $updateData['created'] = $this->timeCreation();
        $updateData['createdBy'] = $this->getUserNumber();
        $updateData['userID'] = $this->getUserNumber();
        parent::delete('userID =' . $this->getUserNumber());
        return parent::insert($updateData);
    }
}
