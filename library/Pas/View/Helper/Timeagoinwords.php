<?php 
/**
 * This class is to help display date of creation etc in words.
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 25 2008
 * Examples are: 23 seconds ago, 1 minute ago, 12 hours ago, 2 weeks ago, and if longer than a month the actual date is returned. This is based upon the class found in cakephp's helpers which is distributed, used and modified under an MIT licence.
*/
class Pas_View_Helper_Timeagoinwords extends Zend_View_Helper_Abstract {


	/** Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
	* @param $date_string is the datetime string drawn from your query or static value
	* @return produces a formatted date string
	* @access private
	*/

	private function fromString($date_string) {
	if (is_integer($date_string) || is_numeric($date_string)) {
	return intval($date_string);
	} else {
	return strtotime($date_string);
	}
	}

	/**  Create the time ago in words display
	* 	To use this within your view use <?php echo $this->TimeAgoInWords($query->field);?>
	* @param string $format can be changed using php's date formatting, here it is configured for display as so: Created on Wednesday 18th June 2008
	* @param string $datetime_string
	* @param boolean $backwards
	* @param boolean $return
	*/

	public function timeagoinwords($datetime_string, $format = 'l jS F Y', 
	$backwards = false, $return = false) {
        $datetime = $this->fromString($datetime_string);

        $in_seconds = $datetime;
        if ($backwards) {
            $diff = $in_seconds - time();
        } else {
            $diff = time() - $in_seconds;
        }

        $months = floor($diff / 2419200);
        $diff -= $months * 2419200;
        $weeks = floor($diff / 604800);
        $diff -= $weeks * 604800;
        $days = floor($diff / 86400);
        $diff -= $days * 86400;
        $hours = floor($diff / 3600);
        $diff -= $hours * 3600;
        $minutes = floor($diff / 60);
        $diff -= $minutes * 60;
        $seconds = $diff;

        if ($months > 0) {
            // over a month old, just show date (mm/dd/yyyy format)
            $relative_date =  date($format, $in_seconds);
            $old = true;
        } else {
            $relative_date = '';
            $old = false;

            if ($weeks > 0) {
                // weeks and days
                $relative_date .= ($relative_date ? ', ' : '') . $weeks . ' week' . ($weeks > 1 ? 's' : '');
                $relative_date .= $days > 0 ? ($relative_date ? ', ' : '') . $days . ' day' . ($days > 1 ? 's' : '') : '';
            } elseif ($days > 0) {
                // days and hours
                $relative_date .= ($relative_date ? ', ' : '') . $days . ' day' . ($days > 1 ? 's' : '');
                $relative_date .= $hours > 0 ? ($relative_date ? ', ' : '') . $hours . ' hour' . ($hours > 1 ? 's' : '') : '';
            } elseif ($hours > 0) {
                // hours and minutes
                $relative_date .= ($relative_date ? ', ' : '') . $hours . ' hour' . ($hours > 1 ? 's' : '');
                $relative_date .= $minutes > 0 ? ($relative_date ? ', ' : '') . $minutes . ' minute' . ($minutes > 1 ? 's' : '') : '';
            } elseif ($minutes > 0) {
                // minutes only
                $relative_date .= ($relative_date ? ', ' : '') . $minutes . ' minute' . ($minutes > 1 ? 's' : '');
            } else {
                // seconds only
                $relative_date .= ($relative_date ? ', ' : '') . $seconds . ' second' . ($seconds != 1 ? 's' : '');
            }
        }

        $ret = $relative_date;

        // show relative date and add proper verbiage
        if (!$backwards && !$old) {
            $ret .= ' ago';
        }
        return $ret . ' ' . $return;

    }
}
?>