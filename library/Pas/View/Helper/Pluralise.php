 <?php

 /**
  * A view helper for pluralisation of quantities
  * @todo this could be made more generic
  * @author Daniel Pett <dpett at britishmuseum.org>
  * @copyright (c) 2014, Daniel Pett
  * @version 1
  * @since 1
  * @category Pas
  * @package Pas_View_Helper
  * An example of usage
  * <pre>
  * $this->pluralise()->setQuantity(10);
  * </pre>
  * Would render:
  * 10 coins
  */
class Pas_View_Helper_Pluralise
{
    /** Quantity of objects to use
     * @access protected
     * @var int
     */
    protected $_quantity = 0;

    /** Nothing string
     * @access protected
     * @var string
     */
    protected $_none = 'no coins';

    /** String to return for single record
     * @access protected
     * @var string
     */
    protected $_singular = '1 coin';

    /** Many coins
     * @access protected
     * @var string
     */
    protected $_plural = 'coins';

    /** get the quantity
     * @access public
     * @return int
     */
    public function getQuantity()
    {
        return $this->_quantity;
    }

    /** Get none
     * @access public
     * @return string
     */
    public function getNone()
    {
        return $this->_none;
    }

    /** get the singular value
     * @access public
     * @return string
     */
    public function getSingular()
    {
        return $this->_singular;
    }

    /** Get the pluralised string
     * @access public
     * @return string
     */
    public function getPlural()
    {
        return $this->_plural;
    }

    /** Set the quantity
     * @access public
     * @param  int                        $quantity
     * @return \Pas_View_Helper_Pluralise
     */
    public function setQuantity(int $quantity)
    {
        $this->_quantity = $quantity;

        return $this;
    }

    /** Set the string for nothing returned
     * @access public
     * @param  string                     $none
     * @return \Pas_View_Helper_Pluralise
     */
    public function setNone(string $none)
    {
        $this->_none = $none;

        return $this;
    }

    /** Set the singular string
     * @access public
     * @param  string                     $singular
     * @return \Pas_View_Helper_Pluralise
     */
    public function setSingular(string $singular)
    {
        $this->_singular = $singular;

        return $this;
    }

    /** Set the plural string
     * @access public
     * @param  string                     $plural
     * @return \Pas_View_Helper_Pluralise
     */
    public function setPlural(string $plural)
    {
        $this->_plural = $plural;

        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_Pluralise
     */
    public function pluralise()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        $html = '';
        if ($this->getQuantity() == 0) {
           $html .= $this->getNone();
        } elseif ($this->getQuantity() == 1) {
            $html .= $this->getSingular();
        } elseif ($this->getQuantity() > 1) {
            $html .= $this->getQuantity();
            $html .= ' ';
            $html = $this->getPlural();
        }

        return $html;
    }
 }
