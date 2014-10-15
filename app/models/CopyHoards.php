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
 * @example  /app/modules/users/controllers/ConfigurationController.php
 */

class CopyHoards extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var type
     */
    protected $_name = 'copyHoards';

    /** The primary key
     * @access protected
     * @var type
     */
    protected $_primary = 'id';

    /** The default options
     * @access protected
     * @var array
     */
    protected $_default = array(
        'broadperiod', 'period1', 'subperiod1',
        'period2', 'subperiod2', 'numdate1',
        'numdate2', 'lastrulerID', 'reeceID',
        'quantityCoins', 'quantityArtefacts', 'quantityContainers',
        'terminalyear1', 'terminalyear2', 'terminalreason',
        'description', 'notes', 'secwfstage',
        'findofnote', 'findofnotereason', 'treasure',
        'treasureID', 'qualityrating', 'materials',
        'recorderID', 'identifier1ID', 'identifier2ID',
        'finderID', 'finder2ID', 'disccircum',
        'discmethod', 'datefound1', 'datefound2',
        'rally', 'rallyID', 'legacyID',
        'other_ref', 'smrrefno', 'musaccno',
        'curr_loc', 'subs_action'
        );

    /** Get the base config
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

    /** Update the config
     * @access public
     * @param array $data The update data
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
