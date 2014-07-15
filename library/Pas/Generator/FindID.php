<?php
/** A class for generating a distinct find ID
 * An example of code use:
 * 
 * <code>
 * <?php
 * $findid = new Pas_Generator_FindID();
 * $data['old_findspotid'] = $findid->generate();
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @version 1
 * @license http://URL name
 * @category Pas
 * @package Pas_Generator
 * @example /app/models/Findspots.php
 */
class Pas_Generator_FindID {

    /** Get the user account
     * @access protected
     * @return string
     * @throws Pas_Exception
     */
    protected function _getAccount(){
        $user = new Pas_User_Details();
        $person = $user->getPerson();
        if($person){
            return $person->institution;
        } else {
            throw new Pas_Exception('User credentials missing', 500);
        }
    }
      
    /** Generate a find ID
     * @access public
     * @return string
     * @throws Pas_Exception_NotAuthorised
     */
    public function generate() {
        if(!is_null($this->_getAccount())) {
        list($usec, $sec) = explode(" ", microtime());
            $suffix =  strtoupper(substr(dechex($sec), 3) 
                    . dechex(round($usec * 15)));
            return $this->_getAccount() . '-' . $suffix;
        } else {
            throw new Pas_Exception_NotAuthorised('Institution missing');
        }
        }
    }

