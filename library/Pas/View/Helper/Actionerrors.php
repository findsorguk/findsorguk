<?php
class Pas_View_Helper_Actionerrors extends Zend_View_Helper_Abstract {
    
	public function actionerrors($class = 'action-errors', $id='action-errors'){
	$result = '';
        
	if (isset($this->_view->actionErrors)) {
		$result .= '<ul class="' . $class . '" id="' . $id . '">' . PHP_EOL;
		foreach ($this->_view->actionErrors as $error) {
		$result .= '<li>' . $error . '</li>' . PHP_EOL;
		}
		$result .= '</ul>' . PHP_EOL;
        }
   	return $result;
    }
}