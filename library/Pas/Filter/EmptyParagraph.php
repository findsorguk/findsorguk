<?php
/**
* Filter extension based on HTML purifioer for allowing Basic HTML on forms and displays
*
*
* @category   Pas
* @package    Filter
* @subpackage Interface
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/

class Pas_Filter_EmptyParagraph implements Zend_Filter_Interface {
    /**
     * Filter out the invalid characters that word puts in.
     * @param string $value
     * @return string
     */
    public function filter($value) {
	$search = '#<p[^>]*>(\s|&nbsp;?)*</p>#';
	$replace = '';
	$clean = preg_replace($search, $replace, $value);
	return $clean;
    }
}