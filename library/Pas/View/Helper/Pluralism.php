 <?php
/**
 * A view helper for determining if a result is singular or plural
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_Pluralism extends Zend_View_Helper_Abstract {
	
	const NONE = 'no results.';
	
	const SINGULAR = '1 result.';
	
	const PLURAL = 'results.';
	
	public function Pluralism($number) {
	$filter = new Zend_Validate_Int();
	if($filter->isValid($number)){
	if ($number === 0) {
	return self::NONE;
	}
	if ($number === 1) {
	return self::SINGULAR;
	}
	return $number . ' ' . self::PLURAL;
	} else {
	return $filter->getMessages(); 	
	}
    }
 }
