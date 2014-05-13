<?php


class Pas_View_Helper_ActionErrors extends Zend_View_Helper_Abstract 
{
	protected $_class = 'action-errors';
	
	protected $_id = 'action-errors';
	
	
	/**
	 * @return the $_class
	 */
	public function getClass() {
		return $this->_class;
	}

	/**
	 * @param $_class the $_class to set
	 */
	public function setClass($_class) {
		$this->_class = $_class;
		return $this;
	}

	/**
	 * @return the $_id
	 */
	public function getID() {
		return $this->_id;
	}

	/** Set the ID
	 * 
	 * @param $_id
	 */
	public function setID($_id) {
		$this->_id = $_id;
		return $this;
	}

	public function actionErrors(){
		return $this;
    }
    
    public function generateHtml() {
    	$result = '';
		if (isset($this->_view->actionErrors)) {
			$result .= '<ul class="';
			$result .= $this->getClass();
			$result .= '" id="';
			$result .= $this->getID();
			$result .= '">' . PHP_EOL;
			foreach ($this->_view->actionErrors as $error) {
				$result .= '<li>' . $error . '</li>' . PHP_EOL;
			}
			$result .= '</ul>' . PHP_EOL;
        }
   		return $result;
    }
    
    public function __toString() {
    	return $this->generateHtml();
    }
}