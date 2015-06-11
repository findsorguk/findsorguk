<?php

/**
 * A view helper for working out difference between two fields
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_View_Helper_Diff extends Zend_View_Helper_Abstract
{
    /** The fields to query and compare
     * @access protected
     * @var array
     */
    protected $_fields = array('description', 'notes');

    /** Compare the strings
     * @param string $key
     * @param string $before
     * @param string $after
     * @access public
     * @return string
     */
    public function diff($key, $before, $after)
    {
        if (in_array($key, $this->_fields)) {
            return $this->htmlDiff($before, $after);
        } else {
            return $after;
        }
    }

    /** Difference returned
     * @access public
     * @return string
     * @param string $old
     * @param string $new
     */
    public function difference($old, $new)
    {
        foreach ($old as $oindex => $ovalue) {
            $nkeys = array_keys($new, $ovalue);
            foreach ($nkeys as $nindex) {
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                    $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if ($matrix[$oindex][$nindex] > $maxlen) {
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }
        }
        if ($maxlen == 0) return array(array('d' => $old, 'i' => $new));

        return array_merge(
            $this->difference(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
            array_slice($new, $nmax, $maxlen),
            $this->difference(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
    }

    /** HTML differences returned
     * @access public
     * @return string
     * @param string $old
     * @param string $new
     */
    public function htmlDiff($old, $new)
    {
        $diff = $this->difference(explode(' ', $old), explode(' ', $new));
        foreach ($diff as $k) {
            if (is_array($k))
                $ret .= (!empty($k['d']) ? "<del>" . implode(' ', $k['d']) . "</del> " : '') .
                    (!empty($k['i']) ? "<ins>" . implode(' ', $k['i']) . "</ins> " : '');
            else $ret .= $k . ' ';
        }
        return $ret;
    }
}
