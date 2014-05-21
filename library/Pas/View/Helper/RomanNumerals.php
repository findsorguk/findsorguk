<?php
/**
 * A view helper for turning dates into Roman numerals
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @author Daniel Pett <dpett at britishmuseum.org>
 */
class Pas_View_Helper_RomanNumerals extends Zend_View_Helper_Abstract
{

    protected $_date;

    protected $_validator;

    protected $_numerals =  array(
        'M'  => 1000, 'CM' => 900, 'D'  => 500,
        'CD' => 400, 'C'  => 100, 'XC' => 90,
        'L'  => 50, 'XL' => 40, 'X'  => 10,
        'IX' => 9, 'V'  => 5, 'IV' => 4,
        'I'  => 1
        );

    public function getDate()
    {
        return $this->_date;
    }

    public function setDate(int $date)
    {
        $this->_date = $date;

        return $this;
    }

    public function getValidator()
    {
        $this->_validator = Zend_Validate_Int();

        return $this->_validator;
    }

    public function getNumerals()
    {
        return $this->_numerals;
    }

    public function romanNumerals()
    {
        return $this;
    } 

    public function validate($date)
    {
       $validator =  $this->getValidator();
       if ($validator->isValud( $date )) {
           return true;
       } else {
           return false;
       }
    }
    public function createDate()
    {
        $html = '';
        if ( $this->validate( $this->getDate() ) ) {
            $n = intval($date);
            foreach ($this->getNumerals() as $roman => $number) {
                //Divide number to get matches
                $matches = intval($n / $number);
                //assign the roman char * $matches
                $html .= str_repeat($roman, $matches);
                //subtract from the number
                $n = $n % $number;
            }
        }

        return $html;
    }

    public function __toString()
    {
        return $this->createDate();
    }
}
