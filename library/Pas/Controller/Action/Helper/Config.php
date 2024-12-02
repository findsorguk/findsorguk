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
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 */
class Pas_Controller_Action_Helper_Config extends Zend_Controller_Action_Helper_Abstract
{
    /** get the config object
     * @access public
     * @return \Zend_Config
     * @throws Zend_Exception
     */
    public function _getConfig()
    {
        return Zend_Registry::get('config');
    }

    /** Proxy method for accessing the config helper
     * @access public
     * @return \Zend_Config
     * @throws Zend_Exception
     */
    public function direct()
    {
        return $this->_getConfig();
    }

    /** Returns the value of a config key
     *
     * @param string ...$keys
     * @return object The value of the key
     * @throws Zend_Exception If the key does not exist
     */
    public function getValue(string ...$keys): string
    {
        $configArray = $this->_getConfig()->toArray();

        foreach ($keys as $key) {
            if (!array_key_exists($key, $configArray)) {
                throw new InvalidArgumentException(
                    "invalid key provided, or key does not exist: $key"
                );
            }

            $configArray = $configArray[$key];
        }

        if (!is_string($configArray)) {
            throw new Zend_Exception("The value is not a string");
        }

        return $configArray;
    }

    public function getWebserviceValue(string ...$keys): string
    {
        return $this->getValue('webservice', ...$keys);
    }
}
