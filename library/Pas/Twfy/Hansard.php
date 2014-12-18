<?php
/** Retrieve a list of parliamentary mentions for PAS
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $twfy = new Pas_Twfy_Hansard();
 * $arts = $twfy->get($search, $this->getPage(), 20);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @since 2/2/2012
 * @version 1
 * @category Pas
 * @package Pas_twfy
 * @subpackage Hansard
  * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Twfy
 * @see http://www.theyworkforyou.com/api/docs/getHansard
 * @example /app/modules/news/controllers/TheyworkforyouController.php
 */
class Pas_Twfy_Hansard extends Pas_Twfy {

    /** Basic method call
     *
     */
     const METHOD = 'getHansard';

     /** Retrieve data
      * @access public
      * @param string $search
      * @param int $page
      * @param int $limit
      * @param string $order
      * @return array
      */
     public function get($search, $page, $limit, $order = 'd'){
         $params = array(
            'key' => $this->_apikey,
            'order' => $order,
            'search' => $search,
            'num' => $limit,
            'page' => $page
        );
     return parent::get(self::METHOD, $params);
     }
}

