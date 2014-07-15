<?php
/** A filter for removing empty paragraphs from content.
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $researchOutline = new Pas_Form_Element_CKEditor('researchOutline');
 * $researchOutline->setLabel('Research outline: ')
 * 		->setRequired(true)
 * 		->addFilters(array('EmptyParagraph'));
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Filter
 * @license http://URL name
 * @version 1
 * @example /app/forms/AcceptUpgradeForm.php
 */

class Pas_Filter_EmptyParagraph implements Zend_Filter_Interface {
    
    /** Filter out the invalid characters that word puts in.
     * @access public
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