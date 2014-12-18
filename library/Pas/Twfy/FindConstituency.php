<?php
/** Retrieve extended details for a single MP
 *
 * An example of use:
 * 
 * <code>
 * <?php
 * $constituency = new Pas_Twfy_FindConstituency();
 * $constituency->get($postcode);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage MpInfo
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Twfy
 * @see http://www.theyworkforyou.com/api/docs/getConstituency
 * @example /app/modules/news/controllers/TheyworkforyouController.php
 */
class Pas_Twfy_FindConstituency extends Pas_Twfy {

    /** Method to call
     *
     */
    const METHOD = 'getConstituency';

    /** Get the extended data
     * @access public
     * @param integer $id
     * @param string $postcode
     * @return type
     */
    public function get($postcode){
        $params = array(
        'key'       =>  $this->_apikey,
        'output'    =>  'js',
        'postcode'        =>  $postcode
        );
        return parent::get(self::METHOD, $params);
    }
}