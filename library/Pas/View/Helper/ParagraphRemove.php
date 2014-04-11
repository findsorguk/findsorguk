<?php
/**
 * A view helper for removing extraneous paragraphs
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @uses Pas_Filter_EmptyParagraph
 */
class Pas_View_Helper_ParagraphRemove extends Zend_View_Helper_Abstract {
	/** Remove extra paragraphs (<p>&nbsp;</p>)
	 * 
	 * @param string $value
	 */
	public function paragraphremove($value)  {
	$filter = new Pas_Filter_EmptyParagraph();
	return $filter->filter($value);
	}
   
 
}

