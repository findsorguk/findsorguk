<?php
/**
* Filter extension for producing URL slugs
*
*
* @category   Pas
* @package    Filter
* @subpackage Interface
* @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
* @license    GNU General Public License

*/
class Pas_Filter_WordChars implements Zend_Filter_Interface
{
    /**
     * Filter out the invalid characters that word puts in.
     * @param string $value
     * @return string
     */
    public function filter($value)  {
	$search = array(chr(0xe2) . chr(0x80) . chr(0x98),  // '
            chr(0xe2) . chr(0x80) . chr(0x99),  // '
            chr(0xe2) . chr(0x80) . chr(0x9c),  // "
            chr(0xe2) . chr(0x80) . chr(0x9d),  // "
            chr(0xe2) . chr(0x80) . chr(0x93),  // em dash
            chr(0xe2) . chr(0x80) . chr(0x94),  // en dash
            chr(0xe2) . chr(0x80) . chr(0xa6), // ...
           	chr(0xC2). chr(0xA0)
	);
   $replace = array('\'', '\'', '"',
   '"', '-', '-',
   '...', ' ');
	return str_replace($search, $replace, $value);
	}
}