<?php
/** An action helper for accessing the config object
 * 
 * An example of code use:
 * 
 * <code>
 * <?php
 * $this->view->delicious = $this->_helper->Config()->webservice->delicious;
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Controller_Action
 * @subpackage Helper
 * @todo Probably deprecate and remove
 * @version 1
 * @example /app/modules/about/controllers/VacanciesController.php
 */
class Pas_Controller_Action_Helper_Config 
    extends Zend_Controller_Action_Helper_Abstract {
   
    /** get the config object
     * @access public
     * @return \Zend_Config
     */
    public function _getConfig(){
        return Zend_Registry::get('config');
    }
    
    /** Proxy method for accessing the config helper
     * @access public
     * @return type
     */
    public function direct(){
        return $this->_getConfig(); 
    }
}