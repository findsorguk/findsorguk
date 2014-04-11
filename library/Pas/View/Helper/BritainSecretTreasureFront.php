<?php
/**
 * A view helper for displaying Britains Secret Treasure bumpf
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_BritainSecretTreasureFront extends Zend_View_Helper_Abstract {

	protected $_date;

        protected $_finishDate = '2012-07-31';

        public function __construct() {
            $this->_date = Zend_Date::now();
        }

        public function BritainSecretTreasureFront(){
//	$difference = $this->_date->isLater($this->_finishDate);
//	if($difference === false){
            return $this->view->partial('structure/Bst.phtml',null);
//        }
	}
}