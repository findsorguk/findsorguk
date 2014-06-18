<?php
/**
 * A class for setting up the rdf client
 * 
 * Example of use:
 * 
 * <code>
 * <?php
 * $client = new Pas_RDF_Client();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @license http://URL name
 * @category Pas
 * @package RDF
 * @subpackage Client
 * @example library/Pas/View/Helper/DbPediaMintRdf.php
 */
class Pas_RDF_Client {
    
    public function __construct(){
        $client = new Zend_Http_Client(
                null, array(
                    'adapter' => 'Zend_Http_Client_Adapter_Curl',
                    'keepalive' => true,
                    'useragent' => "findsorguk",
                    'timeout' => 30
                    )
                );
        EasyRdf_Http::setDefaultHttpClient($client);
	}
}