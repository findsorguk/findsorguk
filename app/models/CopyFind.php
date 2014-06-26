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
 * @licenseGNU General Public License
 * @version 1
 * @since 22 September 2011
 * @example  /app/modules/users/controllers/ConfigurationController.php
 */

class CopyFind extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var type
     */
    protected $_name = 'copyFind';

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
        'description', 'finderID', 'other_ref',
        'datefound1', 'datefound2', 'culture',
        'discmethod', 'disccircum', 'notes',
        'objecttype', 'classification', 'subclass',
        'inscription', 'objdate1period', 'objdate2period',
        'broadperiod', 'numdate1', 'numdate2',
        'material1', 'material2', 'manmethod',
        'decmethod', 'surftreat', 'decstyle',
        'preservation', 'completeness', 'reuse',
        'reuse_period', 'length', 'width',
        'thickness', 'diameter', 'weight',
        'height', 'quantity', 'curr_loc',
        'recorderID', 'finder2ID', 'identifier1ID',
        'identifier2ID', 'findofnotereason', 'findofnote',
        'numdate1qual', 'numdate2qual','objdate1cert',
        'objdate2cert',	'treasure', 'treasureID',
        'subs_action', 'musaccno', 'smr_ref',
        'objdate1subperiod','objdate2subperiod'
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
