<?php
/** Filter extension for producing URL slugs
 *
 * An example of code use:
 * 
 * <code>
 * <?php
 * $researchOutline = new Pas_Form_Element_CKEditor('researchOutline');
 * $researchOutline->setLabel('Research outline: ')
 * 		->setRequired(true)
 * 		->addFilters(array('WordChars'))
 * 		->addErrorMessage('Outline must be present.');
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_Filter
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/forms/AcceptUpgradeForm.php
 */
class Pas_Filter_WordChars implements Zend_Filter_Interface
{
    /** Filter out the invalid characters that word puts in.
     * @param string $value
     * @return string
     */
    public function filter($value)  {
	$search = array(
            chr(0xe2) . chr(0x80) . chr(0x98),  // '
            chr(0xe2) . chr(0x80) . chr(0x99),  // '
            chr(0xe2) . chr(0x80) . chr(0x9c),  // "
            chr(0xe2) . chr(0x80) . chr(0x9d),  // "
            chr(0xe2) . chr(0x80) . chr(0x93),  // em dash
            chr(0xe2) . chr(0x80) . chr(0x94),  // en dash
            chr(0xe2) . chr(0x80) . chr(0xa6), // ...
            chr(0xC2). chr(0xA0)
	);
        $replace = array(
            '\'', '\'', '"',
            '"', '-', '-',
            );
	return str_replace($search, $replace, $value);
    }
}