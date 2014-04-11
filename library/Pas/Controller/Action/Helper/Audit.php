<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author Katiebear
 */
class Pas_Controller_Action_Helper_Audit 
    extends Zend_Controller_Action_Helper_Abstract {
    
    public function getTimeForForms() {
	return Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
    }
    
    public function editID(){
        return md5($this->getTimeForForms());   
    }
    
    public function direct( $auditData, $oldData, $model, $recordID, $entityID ){
//	Zend_Debug::dump($oldData);
//	Zend_Debug::dump($auditData);
	$model = new $model();
//	Zend_Debug::dump($model);
	
	
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
                $auditData[$item] = stripslashes($auditData[$item]);
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
    $fieldarray[$ix]['recordID']     = $recordID;
   
    $fieldarray[$ix]['entityID']     = $entityID;
  
    $fieldarray[$ix]['editID']     = $this->editID();
    $fieldarray[$ix]['fieldName']     = $field_id;
    $fieldarray[$ix]['beforeValue']    = $old_value;
    
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
    $fieldarray[$ix]['recordID']     = $recordID;
    $fieldarray[$ix]['entityID']     = $entityID;
    $fieldarray[$ix]['editID']     = $this->editID();
    $fieldarray[$ix]['fieldName']     = $field_id;
    $fieldarray[$ix]['afterValue']    = $new_value;
		
    } 
	
    
	
    $fieldarray = array_filter($fieldarray,array($this,'filteraudit'));
    foreach($fieldarray as $f){
	foreach ($f as $key => $value) {
    if (is_null($value) || $value=="") {
       $f[$key] = NULL;
      }
    }
//    Zend_Debug::dump($f);
	
    $audit = new $model();
    $auditBaby = $audit->add($f);
//    Zend_Debug::dump($auditBaby);
//    exit;
    }
}
   protected function filteraudit($fieldarray) {
    if ($fieldarray['afterValue'] != $fieldarray['beforeValue']) {
    return true;
        }
    }
    }