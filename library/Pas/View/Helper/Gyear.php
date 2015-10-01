<?php

/**
 * A view helper for rendering georgian years for the RDF views.
 *
 * An example of how to use this:
 *
 * <code>
 * <?php
 * echo $this->geYear()->setDate(-200);
 * ?>
 * </code>
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @copyright Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see  Zend_View_Helper_Abstract
 * @author  Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since   31 October 2015
 */
class Pas_View_Helper_Gyear extends Zend_View_Helper_Abstract
{
    /** The date to check
     * @access protected
     * @var integer
     */
    protected $_date;

    /** Function for returning the correct date format
     * @access public
     * @return \Pas_View_Helper_AdBc
     */
    public function gyear()
    {
        return $this;
    }

    /** Magic method
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->html();
        } catch (Exception $e) {

        }
    }

    /** Function for returning html
     *
     * @return string
     */
    public function html()
    {
        $html = '';
        $date = $this->getDate();
        if ($date < 0) {
            $year = abs($date);
            if (strlen($year) < 4) {
                $html = '-' . str_pad($year, 4, '0', STR_PAD_LEFT);
            } else {
                $html .= '-' . $year;
            }
        } elseif ($date > 0) {
            $html .= abs($date);
        } elseif ($date == 0) {
            $html .= '';
        }
        return $html;
    }

    /** Get the date to use in the class
     * @access public
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }

    /** Set the date to use for the class
     * @access public
     * @param  int $date
     * @return \Pas_View_Helper_AdBc
     */
    public function setDate($date)
    {
        $this->_date = $date;
        return $this;
    }
}