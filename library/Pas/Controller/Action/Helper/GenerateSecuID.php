<?php
/** An action helper for generating a secure ID number.
 * 
 * This helper generates a string that is used as the glue to tie database 
 * records together.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $secuid = $this->_helper->GenerateSecuID();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/database/controllers/PublicationsController.php
 */
class Pas_Controller_Action_Helper_GenerateSecuID extends Zend_Controller_Action_Helper_Abstract {
	
    /** The proxy function to get the secure ID number
     * @access public
     * @return string
     */
    public function direct() {
        $generator = new Pas_Generator_SecuID();
        return $generator->secuid();
    }
}