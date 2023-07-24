<?php
/** A solr class for dealing with sensitive fields
 *
 * An example of use:
 * 
 * <code>
 * <?php
 * $processor = new Pas_Solr_SensitiveFields();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Solr
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /library/Pas/Solr/Handler.php
 */
class Pas_Solr_SensitiveFields {
    
    /**  Check if the data needs cleaning
     * @access public
     * @param array $data
     * @param string $role
     * @param string $core
     */
    public function cleanData($data, $role = null, $core = null, $format = null){
        $sensitiveData = new Pas_Filter_SensitiveData();
        return $sensitiveData->cleanData($data, $format);
    }
}


