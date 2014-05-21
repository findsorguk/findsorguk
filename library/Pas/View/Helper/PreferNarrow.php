<?php
/**
 * A view helper to render thesauri terms
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @since 1
 * @package Pas
 * @category Pas_View_Helper
 *
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
    public function getTerm()
    {
        return $this->_term;
    }

    /** Set the term to query
     * @access public
     * @param  string                        $term
     * @return \Pas_View_Helper_PreferNarrow
     */
    public function setTerm(string $term)
    {
        $this->_term = $term;

        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_PreferNarrow
     */
    public function preferNarrow()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        switch ($this->getTerm()) {
            case 'P':$
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
