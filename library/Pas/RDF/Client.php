<?php
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