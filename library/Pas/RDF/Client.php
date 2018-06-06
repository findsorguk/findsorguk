<?php

/** A class for setting up the rdf client
 *
 * Example of use:
 *
 * <code>
 * <?php
 * $client = new Pas_RDF_Client();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @category Pas
 * @package RDF
 * @subpackage Client
 * @example library/Pas/View/Helper/DbPediaMintRdf.php
 * @uses Zend_Http_Client
 * @uses Easy_Rdf_Http
 *
 */
class Pas_RDF_Client
{
    /** Construct the function
     * @access public
     */
    public function __construct()
    {
        $client = new Zend_Http_Client(
            null, array(
                'adapter' => 'Zend_Http_Client_Adapter_Curl',
                'keepalive' => true,
                'useragent' => "findsorguk",
                'timeout' => 30
            )
        );
        \EasyRdf\Http::setDefaultHttpClient($client);
    }
}