<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Postcode
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 */
class Pas_Validate_ValidPostCode extends Zend_Validate_PostCode
{
 const NOT_POSTCODE = 'notPostcode';

    protected $_messageTemplates = array(
        self::NOT_POSTCODE => 'value is not a valid UK postcode'
    );

	protected $_messageVariables = array(

	);


    public function isValid($value, $context = null)
    {
        $value = (string) $value;
        $this->_setValue($value);
        $ok = true;
        
        // Normalise the string into uppercase A-Z and 0-9 only. (Space is removed if it was supplied!)
       $value = preg_replace('/[^A-Z0-9]/', '', strtoupper($value));
       
       // Min valid length is 5 - check first so that the substr's below work as expected.
       if (strlen($value) < 5) {
           $this->_error(self::NOT_POSTCODE);
           return false;   
       }
       
       // Split the postcode into it's two parts. Part 2 is always the last 3 chars.
       $part1 = substr($value, 0, strlen($value) - 3);
       $part2 = substr($value, strlen($value) - 3, 3);
        
       // Check the normalised parts using rules at :-
       //   http://www.cabinetoffice.gov.uk/govtalk/schemasstandards/e-gif/datastandards/address/postcode.aspx
       
       // First half must match these 4 rules...
       $p1 = '[ABCDEFGHIJKLMNOPRSTUWYZ]';      // required
       $p2 = '[ABCDEFGHKLMNOPQRSTUVWXY0-9]';   // required
       $p3 = '[ABCDEFGHJKSTUW0-9]?';           // optional
       $p4 = '[ABEHMNPRVWXY0-9]?';             // optional
       $part1Pattern = '/^('.$p1.$p2.$p3.$p4.')/';
       
       // Part two must match this pattern...
       $part2Pattern = '/([0-9][ABDEFGHJLNPQRSTUWXYZ][ABDEFGHJLNPQRSTUWXYZ])/';
       
       // Or the special case
       $specialPattern = 'GIR0AA';
       
       if (preg_match($part1Pattern, $part1) == 0) {
           $ok = false;
       }
       if (preg_match($part2Pattern, $part2) == 0) {
           $ok = false;
       }
       if ($value == $specialPattern) {
           $ok = true;
       }
       
       if (!$ok) {
           $this->_error(self::NOT_POSTCODE);
           return false;
       }

        return true;
    }
}