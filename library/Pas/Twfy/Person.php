<?php

/** Retrieve a person's details from twfy
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $person =  new Pas_Twfy_Person();
 * $data = $person->get($id);
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
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses Pas_Twfy
 * @uses Pas_Twfy_Exception
 * @see http://www.theyworkforyou.com/api/docs/getPerson
 * @example /app/modules/news/controllers/TheyworkforyouController.php
 */
class Pas_Twfy_Person extends Pas_Twfy
{

    /** The correct method to use
     *
     */
    const METHOD = 'getPerson';

    /** Get a person's data
     *
     * @param int $id
     * @return object
     * @throws Pas_Twfy_Exception
     */
    public function get($id, $params = array())
    {
        if (is_numeric($id) && !is_null($id)) {
            $params = array(
                'key' => $this->_apikey,
                'output' => 'js',
                'id' => $id
            );
            return parent::get(self::METHOD, $params);
        } else {
            throw new Pas_Twfy_Exception('Person ID problems', 500);
        }
    }
}