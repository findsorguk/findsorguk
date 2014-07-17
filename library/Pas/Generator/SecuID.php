<?php
/** A class for generating a secure ID for gluing data together.
 * 
 * An example of use:
 * 
 * <code>
 * <?php
 * $generator = new Pas_Generator_SecuID();
 * $secuid = $generator->secuid();
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Generator
 * @version 1
 * @example /library/Pas/Controller/Action\Helper/GenerateSecuID.php
 *
 */
class Pas_Generator_SecuID {

    /** The database string to use
     */
    const  DBASE_ID = 'PAS';

    /**The secure ID instance
     */
    const  SECURE_ID = '001';

    /** created the secuid
     * @access public
     * @return string
     */
    public function secuid() {
        list($usec, $sec) = explode(" ", microtime());
        $ms = dechex(round($usec * 4080));
        while(strlen($ms) < 3) {
            $msNew = '0' . $ms; 
        }
        while(strlen($ms)<3) {
            $msNew = '0' . $ms; 
        }
        return strtoupper(self::DBASE_ID . dechex($sec) . self::SECURE_ID . $msNew);
    }
}