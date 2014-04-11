<?php
/**
 *
 * @author dpett
 * @version
 */

/**
 * ResultsQuantityChooser helper
 *
 * @uses viewHelper Pas_View_Helper
 */
class Pas_View_Helper_ResultsSorter extends Zend_View_Helper_Abstract{

	protected $_fields = array(
		'Date created'	=> 'created',
		'Object type' 	=> 'objectType',
		'Broad period'	=> 'broadperiod',
		'Recording institution'	=> 'institution',
		'Workflow status' => 'workflow',
		'Updated'       => 'updated'
	);

	protected $_defaultDirection = 'desc';

	protected $_defaultSort = 'created';

	protected $_request;

	protected $_direction = array('descending' => 'desc', 'ascending' => 'asc');

	public function __construct(){
		$this->_request = Zend_Controller_Front::getInstance()->getRequest()->getParams();
	}
	/**
	 *
	 */
	public function resultsSorter($results) {
		if($results){
			$html = $this->_buildHtmlField();
			$html .= $this->_buildHtmlDirection();
			return $html;
		} else {
			return null;
		}

	}

	protected function _buildHtmlField(){
		$request = $this->_request;
		$html = '<p>Sort your search by: </p>';
		$html .= '<ul>';
		foreach($this->_fields as $k => $v){
			$request['sort'] = $v;
			$html .= '<li><a href="' . $this->view->url($request, 'default', true) . '"';
			if(array_key_exists('sort', $this->_request) && $this->_request['sort'] === $v){
				$html .= ' class="highlight" ';
			}elseif (!array_key_exists('sort', $this->_request) && $v === 'created'){
				$html .= ' class="highlight" ';
			}
			$html .= '>' . $k . '</a></li>';
		}
		$html .= '</ul>';
		return $html;
	}

	protected function _buildHtmlDirection(){
		$request = $this->_request;
		$html = '<p>Which direction? ';
		$sorter = array();
		foreach($this->_direction as $k => $v){
			$request['direction'] = $v;
			switch($v){
				case 'desc':
					$icon = 'down';
					break;
				case 'asc':
					$icon = 'up';
					break;
			}
		$sort = '<a href="' . $this->view->url($request, 'default', true) . '" '; 
		if(array_key_exists('direction', $this->_request) && $this->_request['direction'] === $v){
				$sort .= ' class="highlight" ';
			} elseif (!array_key_exists('direction', $this->_request) && $v === 'desc'){
				$sort .= ' class="highlight" ';
			}
			$sort .= '>' . $k . '<i class="icon-arrow-' . $icon .'"></i></a> ';
			$sorter[] = $sort;
		}

		$html .= implode($sorter, ' | ') . '</p>';
		return $html;
	}


}

