<?php
/**
 * A view helper to render thesauri term's scope.
 *
 * This helper takes data from the English Heritage thesaurus and uses the
 * status column to determine whether hey can be either preferred or narrow in
 * their usage. It is very limited in use and probably overkill.
 *
 * An example of use case:
 * <code>
 * <?php
 * echo $this->preferNarrow()->setTerm('P');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @package View
 * @subpackage Helper
 * @category Pas
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/views/scripts/database/objectLister.phtml
 */
class Pas_View_Helper_PreferNarrow extends Zend_View_Helper_Abstract
{
    /** The term
     * @access protected
     * @var string
     */
    protected $_term;

    /** Get the term to query
     * @access public
     * @return string
     */
    public function getTerm() {
        return $this->_term;
    }
    /** Set the term to query
     * @access public
     * @param  string $term
     * @return \Pas_View_Helper_PreferNarrow
     */
    public function setTerm( $term ) {
        $this->_term = $term;
        return $this;
    }
    /** The function to return
     * @access public
     * @return \Pas_View_Helper_PreferNarrow
     */
    public function preferNarrow() {
        return $this;
    }
    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        switch ($this->getTerm()) {
            case 'P':
                $p = 'Preferred term';
                break;
            case 'N':
                $p = 'Narrow term';
                break;
            default:
                $p = "Invalid term";
                break;
        }
        return $p;
    }
}