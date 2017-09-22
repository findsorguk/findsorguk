<?php
/**
 * A model for interfacing with the copy find table
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new CopyFind();
 * $data = $model->getConfig();
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
 * @example /app/modules/users/controllers/ConfigurationController.php
*/

class CopyCoin extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'copyCoin';

    /** The primary key
     * @access protected
     * @var string
     */
    protected $_primary = 'id';

    /** The cache key
     * @access protected
     * @var string
     * @todo Introduce cache
     */
    protected $_key;

    /** Get the cache key
     * @access public
     * @return string
     */
    public function getKey() {
        $this->_key = md5('coinConfig' . $this->getUserNumber());
        return $this->_key;
    }

    /** The default array
     * @access protected
     * @var type
     */
    protected $_default = array(
        'ruler_id', 'ruler_qualifier', 'denomination',
        'denomination_qualifier', 'mint_id', 'mint_qualifier',
        'status', 'status_qualifier', 'obverse_description',
        'obverse_inscription', 'reverse_description', 'reverse_inscription',
        'reverse_mintmark', 'degree_of_wear', 'die_axis_measurement',
        'die_axis_certainty', 'moneyer', 'reeceID',
        'revtypeID', 'revTypeID_qualifier', 'ruler2_id',
        'ruler2_qualifier', 'tribe' , 'tribe_qualifier',
        'geographyID', 'geography_qualifier', 'bmc_type',
        'allen_type', 'mack_type', 'rudd_type',
        'va_type','numChiab', 'categoryID',
        'typeID', 'type', 'initial_mark',
        'greekstateID', 'revtypeID'
        );

    /** Get the config
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

    /** Update the configuration
     * @access public
     * @param array $data
     * @return integer
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