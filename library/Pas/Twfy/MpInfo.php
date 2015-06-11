<?php
/** Retrieve extended details for a single MP
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage MpInfo
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Twfy
 * @see http://www.theyworkforyou.com/api/docs/getMPInfo
 */
class Pas_Twfy_MpInfo extends Pas_Twfy {

    /** Method to call
     */
    const METHOD = 'getMPInfo';

    /** Get the extended data
     * @access public
     * @param integer $id
     * @param array $fields
     * @return type
     */
    public function get($id = null, array $fields){
        $params = array(
             'key' => $this->_apikey,
             'id' => $id,
             'fields' => $fields
        );
        return parent::get(self::METHOD, $params);
    }
}