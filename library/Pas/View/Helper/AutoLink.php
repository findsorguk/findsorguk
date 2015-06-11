<?php
/**
 * This view helper class is to automatically parse and display urls
 *
 * Example of use:
 *
 * <code>
 * <?php
 * echo $this->autoLink()->setText($text);
 * ?>
 * </code>
 * @category   Pas
 * @package View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Zend_View_Helper_Abstract
 * @since September 13 2011
 * @example /library/Pas/View/Helper/LatestTweets.php
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @example /library/Pas/View/Helper/LatestTweets.php
*/
class Pas_View_Helper_AutoLink extends Zend_View_Helper_Abstract {

    /** The text to autolink
     * @access protected
     * @var string
     */
    protected $_text;

    /** Get the text to autolink
     * @access public
     * @return string
     */
    public function getText() {
        return $this->_text;
    }

    /** Set the text to autolink
     * @access public
     * @param string $text
     * @return \Pas_View_Helper_AutoLink
     */
    public function setText($text) {
        $this->_text = $text;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_AutoLink
     */
    public function autoLink() {
        return $this;
    }

    public function __toString() {
        return $this->auto_link_text($this->getText());
    }

    /** The auto link function
     *
     * To get this to work, you need to specify your functions as static
     * functions, and use the following as callback function:
     * <code>
     * preg_replace($pattern, array(get_class($this), 'functionName'), $text);
     * </code>
     *
     * @see http://stackoverflow.com/a/1971451 for documentation of this function
     * @see http://www.php.net/manual/en/language.pseudo-types.php#language.types.callback
     * @param string $text
     * @return string
     */
    public function auto_link_text( $text ) {
        $pattern  = '#\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
        return preg_replace_callback($pattern,
                array(get_class($this),'autoLinkTextCallback'), $text);
    }

    /** Static callback function for auto linking
     * @access static
     * @param type $matches
     * @return type
     */
    static function autoLinkTextCallback( $matches ) {
        $max_url_length = 50;
        $max_depth_if_over_length = 2;
        $ellipsis = '&hellip;';

        $url_full = $matches[0];
        $url_short = '';

        if (strlen($url_full) > $max_url_length) {
            $parts = parse_url($url_full);
            $url_short = $parts['scheme'] . '://'
                    . preg_replace('/^www\./', '', $parts['host']) . '/';

            $path_components = explode('/', trim($parts['path'], '/'));
            foreach ($path_components as $dir) {
                $url_string_components[] = $dir . '/';
            }

            if (!empty($parts['query'])) {
                $url_string_components[] = '?' . $parts['query'];
            }

            if (!empty($parts['fragment'])) {
                $url_string_components[] = '#' . $parts['fragment'];
            }

            for ($k = 0; $k < count($url_string_components); $k++) {
                $curr_component = $url_string_components[$k];
                if ($k >= $max_depth_if_over_length || strlen($url_short)
                        + strlen($curr_component) > $max_url_length) {
                    if ($k == 0 && strlen($url_short) < $max_url_length) {
                        // Always show a portion of first directory
                        $url_short .= substr($curr_component, 0,
                                $max_url_length - strlen($url_short));
                    }
                    $url_short .= $ellipsis;
                    break;
                }
                $url_short .= $curr_component;
            }

        } else {
            $url_short = $url_full;
        }

        return "<a rel=\"nofollow\" href=\"$url_full\">$url_short</a>";
    }
}