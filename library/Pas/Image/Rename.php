<?php
/** A very simple and limited class for string stripping
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Image
 * @since 3/2/12
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */
class Pas_Image_Rename
{

    /** A function for removing characters from string
     * @access public
     * @return string
     */
    public function strip($filename, $extension)
    {
        return preg_replace('/\W+/', '', $filename) . '.' . $extension;
    }
} 