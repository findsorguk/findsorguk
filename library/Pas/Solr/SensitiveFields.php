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

    /**  The array of roles that can view the full data set.
     * @access public
     * @var array
     */
    protected $_allowed = array(
        'fa', 'flos', 'admin',
        'treasure', 'research', 'hero',
        'hoard'
    );

    /** The array of people who can see the personal data set.
     * @access public
     * @var array
     */
    protected $_personal = array('fa','flos','admin','treasure');

    /**  Check if the data needs cleaning
     * @access public
     * @param array $data
     * @param string $role
     * @param string $core
     */    
    public function cleanData( $data, $role, $core ){
        if(!in_array($role, $this->_allowed) && $core == 'beowulf'){
           return $this->_processGeoData($data, $role);
        } else {
            return $data;
        }
    }

    /** Process the data for geo sensitive fields
     * @access public
     * @param array $data
     * @param string $role
     */    
    protected function _processGeoData($data, $role){
        if(is_array($data)){
		$clean = array();
            foreach($data as $record){
            	//If the knownas key exists and is filled in, then it needs restricting
                if(array_key_exists('knownas', $record) && !is_null($record['knownas'])){
                // State that the grids are restricted.
                    $record['parish'] = 'Restricted Access';
                    $record['fourFigure'] = 'Restricted Access';
				// Unset the fourfigure lat/lon
                    unset($record['fourFigureLat']);
                    unset($record['fourFigureLon']);
                } else if(array_key_exists('gridref', $record) && !array_key_exists('knownas', $record)) {
                //Convert the fourfigure grid to required data
                    
                    $record['gridref'] =  $record['fourFigure'];
                    $record['fourFigure'] = $record['fourFigure'];
                }
                //Remove the finder key
                if(array_key_exists('finder', $record) && !in_array($role, $this->_personal)){
                unset($record['finder']);
            }
            $clean[] = $record;
            }
        }
        return $clean;
    }

}


