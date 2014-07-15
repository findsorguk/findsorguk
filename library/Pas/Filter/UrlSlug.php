<?php
/** Filter extension for producing URL slugs
 *
 * An example of code use:
 * 
 * <code>
 * <?php
 * $slug = new Zend_Form_Element_Text('slug');
 * $slug->setLabel('Page slug: ')
 *              ->setAttrib('size',50)
 *              ->addFilter('UrlSlug')
 *              ->addFilters(array('StripTags','StringTrim'))
 *              ->setRequired(true);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Filter
 * @version 1
 * @license http://URL name
 * @example E:\GitHubProjects\findsorguk\app\forms\ContentForm.php
*/
class Pas_Filter_UrlSlug implements Zend_Filter_Interface {

    /** Filter the input
     * @access public
     * @param string $slug The string to sanitise
     * @return string $result the cleaned result
     */
    public function filter($slug) {
        $result = strtolower($slug);
        $result = preg_replace('/[^a-z0-9\s-]/', '', $result);
        $result = trim(preg_replace('/\s+/', ' ', $result));
        $result = trim(substr($result, 0, 45));
        $result = preg_replace('/\s/', '-', $result);
        return $result;
    }
}