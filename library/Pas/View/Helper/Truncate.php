<?php
/** Truncate string helper
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->truncate($string)->toLength(100)->withPostfix(' ');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @since 1
 * @uses viewHelper Pas_View_Helper
 * @copyright (c) 2014, Daniel Pett
 * @license http://URL GNU
 * @category Pas
 * @package Pas_View_Helper
 * @example /app/modules/romancoins/views/scripts/emperors/data.json.phtml
 * @todo Use this more often throughout site
 */
class Pas_View_Helper_Truncate extends Zend_View_Helper_Abstract {

    /** The string to truncate
     * @access protected
     * @var string
     */
    protected $_string;

    /** The length to truncate
     * @access protected
     * @var int
     */
    protected $_length;

    /** Post fix value
     * @access protected
     * @var string
     */
    protected $_postfix;

    /** Cut at space
     * @access protected
     * @var boolean
     */
    protected $_cutatspace = true;

    /** Truncate the string
     *
     * @param  string $string
     * @return \Pas_View_Helper_Truncate
     */
    public function truncate($string) {
        $this->_string = trim($string);
        $this->_defaultValues();
        return $this;
    }

    /** Set default values
     *
     */
    private function _defaultValues() {
        $this->toLength(100);
        $this->withPostfix('&#0133;');
    }

    /** Cut midword
     * @access public
     * @return \Pas_View_Helper_Truncate
     */
    public function midword() {
        $this->_cutatspace = false;
        return $this;
    }

    /** To a certain length
     * @access public
     * @param  int $int
     * @return \Pas_View_Helper_Truncate
     */
    public function toLength($int) {
        $this->_length = (int) $int;
        return $this;
    }

    /** With the postfix string
     * @access public
     * @param  string $str
     * @return \Pas_View_Helper_Truncate
     */
    public function withPostfix($str) {
        $this->_postfix = $str;
        return $this;
    }

    /** Render function
     * @access public
     * @return string
     */
    public function render() {
    // Return empty string if max length < 1
        if ($this->_length < 1) {
            return '';
            }

    // Return full string if max length >= string length

        if ($this->_length >= strlen($this->_string)) {
            return $this->_string;

        }

    // Return truncated string

        if ($this->_cutatspace) {

            while (strlen($this->_string) > $this->_length) {

                $cutPos = strrpos($this->_string, ' ', -1);
                if ($cutPos === false) {
                    // no spaces left, whole string truncated
                    return '';
                }
                $this->_string = trim(substr($this->_string, 0, $cutPos));
                }

                } else {
                    $this->_string = trim(substr($this->_string, 0, $this->_length));
                }

                return $this->_string . $this->_postfix;
        }

        /** Magic method
         * @access public
         * @return string
         */
        public function __toString() {
            return $this->render();
        }
}