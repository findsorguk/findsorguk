<?php
/**
 * A view helper for displaying online accounts as xml
 *
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 */

class Pas_View_Helper_OnlineAccount extends Zend_View_Helper_Abstract {

    /** Display the accounts as foaf
     *
     * @param integer $id
     */
    public function OnlineAccount($id) {
        $accts = new OnlineAccounts();
        $data = $accts->getAccounts($id);
        return $this->view->partialLoop('partials/xml/foafAccts.phtml',$data);
    }

}