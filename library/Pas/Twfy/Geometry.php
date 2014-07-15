<?php
/** Retrieve the geometry for a constituency from twfy
 * 
 * An example of code:
 * 
 * <code>
 * <?php
 * $geo = new Pas_Twfy_Geometry();
 * $const = urldecode($this->_getParam('constituency'));
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @uses Pas_Twfy_Exception
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Geometry
 * @license GNU
 * @example /library/Pas/View/Helper/FindsOfNoteConst.php 
 * @see http://www.theyworkforyou.com/api/docs/getGeometry
 */
class Pas_Twfy_Geometry extends Pas_Twfy {

    /** The method to use
     *
     */
    const METHOD = 'getGeometry';

    /** Get the geometry of a constituency
     * @access public
     * @param string $constituency
     * @return array
     * @throws Pas_Twfy_Exception
     */
    public function get($constituency) {
        if(!is_null($constituency) && is_string($constituency)){
            $params = array(
                'key'       => $this->_apikey,
                'name'      => $constituency,
                'format'    => $this->_format
                );
            return parent::get(self::METHOD, $params);
        } else {
            throw new Pas_Twfy_Exception('No constituency provided');
        }
    }

}