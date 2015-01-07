<?php
/**
 * View helper for turning bytes to filesize in human format
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->humanFileSize()->setSize($size);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @since September 28 2011
 * @category Pas
 * @package View_Helper
 * @subpackage Abstract
 * @example /app/views/scripts/partials/database/images/image.phtml
 * @see http://stackoverflow.com/questions/15188033/human-readable-file-size
 *
 */
class Pas_View_Helper_HumanFileSize extends Zend_View_Helper_Abstract {

    /** The size of the file
     * @access protected
     * @var int
     */
    protected $_size;

    /** Get the size of file
     * @access public
     * @return int
     */
    public function getSize() {
        return $this->_size;
    }

    /** Set the filesize
     * @access public
     * @param int $size
     * @return \Pas_View_Helper_HumanFileSize
     */
    public function setSize($size) {
        $this->_size = $size;
        return $this;
    }
    
    /** Return the function
     * @access public
     * @return \Pas_View_Helper_HumanFileSize
     */
    public function humanFileSize() {
        return $this;
    }

    /** To String function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->humanSize($this->getSize());
    }

    /** Returns a size in a human-readable form from a byte count.
     * @access public
     * @param int $bytes
     * @return string
     */
    public function humanSize($bytes) {
        if ($bytes < 1024){
            return "$bytes Bytes";
        }
        $units = ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        foreach ($units as $i => $unit) {
        // The reason for this threshold is to avoid e.g., "1000 KB",
        // instead jumping from e.g., "999 KB" to "0.97 MB".
            $multiplier = pow(1024, $i + 1);
            $threshold = $multiplier * 1000;
            if ($bytes < $threshold) {
                $size = $this->formatToMinimumDigits($bytes / $multiplier, false);
            return "$size $unit";
            }
        }
    }


    /**  Efficiently calculates how many digits the integer portion of a number has.
     * @access public
     * @param type $number
     * @return int
     */
    public function digits($number) {
        // Yes, I could convert to string and count the characters, but this is faster and cooler.
        $log = log10($number);
        if ($log < 0) {
            return 1;
            }
            return floor($log) + 1;
        }

    /** Formats a number to a minimum amount of digits.
     * In other words, makes sure that a number has at least $digits on it, even if
     * that means introducing redundant decimal zeroes at the end, or rounding the
     * ones present exceeding the $digits count when combined with the integers.
     * For example:
     * formatToMinimumDigits(10)           // 10.0
     * formatToMinimumDigits(1.1)          // 1.10
     * @param int $value
     * @param boolean $round
     * @param int $digits
     * @return string
     */
    public function formatToMinimumDigits($value, $round = true, $digits = 3) {
        $integers = floor($value);
        $decimalsNeeded = $digits - $this->digits($integers);
        if ($decimalsNeeded < 1) {
            return $integers;
        } else {
            if ($round){
                // This relies on implicit type casting of float to string.
                $parts = explode('.', round($value, $decimalsNeeded));
                // We re-declare the integers because they may change
                // after we round the number.
                $integers = $parts[0];
            } else {
                // Again, implicit type cast to string.
                $parts = explode('.', $value);
            }
            // And because of the implicit type cast, we must guard against
            // 1.00 becoming 1, thus not exploding the second half of it.
            $decimals = isset($parts[1]) ? $parts[1] : '0';
            $joined = "$integers.$decimals".str_repeat('0', $digits);
            return substr($joined, 0, $digits + 1);
        }
    }
}