<?php
/**
 * A view helper for automatically inserting period name from integer
 * This function is a bit of a waste of space!
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    GNU Public
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett, The British Museum
 * @uses Zend_Exception
 * @version 2
 * @since 1
 *
 */
class Pas_View_Helper_AdminPeriod extends Zend_View_Helper_Abstract
{
    /** The period variable
     *
     * @var integer
     */
    protected $_period;

    /** Get the period to render
     * @access public
     * @return string
     */
    public function getPeriod()
    {
        return $this->_period;
    }

    /** Set the period
     * @access public
     * @param  int                          $period
     * @return \Pas_View_Helper_AdminPeriod
     */
    public function setPeriod(int $period)
    {
        $this->_period = $period;

        return $this;
    }

    /** Base function for class
     * @access public
     * @return \Pas_View_Helper_AdminPeriod
     */
    public function adminPeriod()
    {
        return $this;
    }

    /** Switch function for determining the html to return
     * @access public
     * @param int period
     * @return string
     */
    public function html(int $period)
    {
        switch ($period) {
            case 21 :
                $periodName = 'Roman';
                break;
            case 47 :
                $periodName = 'Early Medieval';
                break;
            case 16 :
                $periodName = 'Iron Age';
                break;
            case 29 :
                $periodName = 'Medieval';
                break;
            case 36 :
                $periodName = 'Post Medieval';
                break;
            case 66 :
                $periodName = 'Greek and Roman Provincial';
                break;
            case 67 :
                $periodName = 'Byzantine';
                break;
        }

        return $periodName;
     }

     /** Magic method to return the html
      *
      * @return string
      */
     public function __toString()
     {
         return $this->html( $this->getPeriod() );
     }
 }
