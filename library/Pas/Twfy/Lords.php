<?php
/** Retrieve a list of lords members from theyworkforyou
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Hansard
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Twfy
 * @see http://www.theyworkforyou.com/api/docs/getLords
 */
class Pas_Twfy_Lords extends Pas_Twfy {

    /** The method to call
     * 
     */
    const METHOD = 'getLords';

    /** Get the data
     * @access public
     * @return array
     */
    public function get(){
        $params = array(
            'key' => $this->_apikey,
        );
        return parent::get(self::METHOD, $params);
     }
}

