<?php
/** Description of Config
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $this->_helper->audit(
 * $updateData,
 * $oldData,
 * 'FindsAudit',
 * $this->_getParam('id'),
 * $this->_getParam('id')
 * );
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @example /app/modules/database/controllers/ArtefactsController.php
 *
 */
class Pas_Controller_Action_Helper_Audit extends Zend_Controller_Action_Helper_Abstract {

    /** Get a time stamp
     * @access public
     * @return string
     */
    public function getTimeForForms() {
	    return Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
    }

    /** Create an edit ID
     * @access public
     * @return string
     */
    public function editID(){
        // take first 25 characters of id created
        return substr(md5($this->getTimeForForms()), 0, 25);
    }

    /** The direct action to run the helper.
     * @access public
     * @param array $auditData
     * @param array $oldData
     * @param string $model
     * @param integer $recordID
     * @param integer $entityID
     */
    public function direct( array $auditData, array $oldData, $model, $recordID, $entityID ){
	    $model = new $model();
        if (!empty($auditData)) {
            unset($auditData['csrf']);
            // look for new fields with empty/null values
            foreach ($auditData as $item => $value) {
                if (empty($value)) {
                    if (!array_key_exists($item, $oldData)) {
                        // value does not exist in $oldarray, so remove from $newarray
                        unset ($auditData[$item]);
                    } // if
                } else {
                    // remove slashes (escape characters) from $newarray
                    if(!is_array($auditData[$item])) {
                        $auditData[$item] = stripslashes($auditData[$item]);
                    }
                } // if
            } // foreach
            // remove entry from $oldarray which does not exist in $newarray
            foreach ($oldData as $item => $value) {
                if (!array_key_exists($item, $auditData)) {
                    unset ($oldData[$item]);
                } // if
            } // foreach
        } //

        $fieldarray   = array();
        $ix           = 0;

        foreach ($oldData as $field_id => $old_value) {
            $ix++;
            $fieldarray[$ix]['recordID'] = $recordID;
            $fieldarray[$ix]['entityID'] = $entityID;
            $fieldarray[$ix]['editID'] = $this->editID();
            $fieldarray[$ix]['fieldName'] = $field_id;
            $fieldarray[$ix]['beforeValue'] = $old_value;

            if (isset($auditData[$field_id])) {
                $fieldarray[$ix]['afterValue'] = $auditData[$field_id];
                // remove matched entry from $newarray
                unset($auditData[$field_id]);
            } else {
                $fieldarray[$ix]['afterValue'] = '';
            } // if
        } // foreach

        // process any unmatched details remaining in $newarray
        foreach ($auditData as $field_id => $new_value) {
            $ix++;
            $fieldarray[$ix]['recordID'] = $recordID;
            $fieldarray[$ix]['entityID'] = $entityID;
            $fieldarray[$ix]['editID'] = $this->editID();
            $fieldarray[$ix]['fieldName'] = $field_id;
            $fieldarray[$ix]['afterValue'] = $new_value;
        }



        $fieldarray = array_filter($fieldarray,array($this,'filteraudit'));
        foreach($fieldarray as $f){
            foreach ($f as $key => $value) {
                if (is_null($value) || $value=="") {
                    $f[$key] = null;
                }
            }
            $audit = new $model();
            $audit->add($f);
        }
    }

    /** Filter the audit array
     * @access protected
     * @param array $fieldarray
     * @return boolean
     */
    protected function filteraudit(array $fieldarray) {
        if ($fieldarray['afterValue'] != $fieldarray['beforeValue']) {
            return true;
        }
    }

}
