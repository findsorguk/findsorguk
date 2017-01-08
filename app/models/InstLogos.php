<?php
/**
 * Model for retrieving institutional logos
 *
 * An example of use:
 * 
 * <code>
 * <?php
 * $logos = new InstLogos();
 * $logoslisted = $logos->getLogosInst($inst);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /library/Pas/View/Helper/InstLogos.php View helper for showing logo
 */
class InstLogos extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'instLogos';

    /** The primary key
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** Get the logo for an institution
    * @param string $inst
    * @return array $data
    * @todo add caching
    */
    public function getLogosInst($inst) {
        $logos = $this->getAdapter();
        $select = $logos->select()
                ->from($this->_name, array('image'))
                ->joinLeft('institutions','institutions.institution = '
                        . $this->_name . '.instID', array())
                ->where('institutions.institution = ?', (string)$inst);
        return  $logos->fetchAll($select);
    }
}