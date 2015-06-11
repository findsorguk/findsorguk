<?php
/**
 * A view helper for removing word inserted crappy characters
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_WordChars extends Zend_View_Helper_Abstract
{
    /**
     * Filter out the invalid characters that word puts in.
     * @param  string $value
     * @return string
     */

    public function WordChars($value)
    {
    $search = array(
    chr(0xe2) . chr(0x80) . chr(0x98),  // '
    chr(0xe2) . chr(0x80) . chr(0x99),  // '
    chr(0xe2) . chr(0x80) . chr(0x9c),  // "
    chr(0xe2) . chr(0x80) . chr(0x9d),  // "
    chr(0xe2) . chr(0x80) . chr(0x93),  // em dash
    chr(0xe2) . chr(0x80) . chr(0x94),  // en dash
    chr(0xe2) . chr(0x80) . chr(0xa6),
    );

    $replace = array(
    '\'', '\'', '"',
    '"', '-', '-',
    '...', '');

    return str_replace($search, $replace, $value);
    }
}
