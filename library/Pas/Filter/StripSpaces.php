<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: $
 */


/**
 * @see Zend_Filter_Interface
 */
require_once 'Zend/Filter/Interface.php';

/**
 * This class strips out spaces
 *
 * @category   Zend
 * @package    Zend_Filter
 * @copyright  Copyright (c) 2005-2007 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd New BSD License
 */
class Pas_Filter_StripSpaces implements Zend_Filter_Interface
{

    /**
     * Holds whether to replace all spaces or not.
     *
     * @var bool
     */
    protected $_replaceAllSpaces;


    /**
     * Enter description here...
     *
     * @throws Zend_Filter_Exception
     * @param bool $replaceAllSpaces
     */
    public function __construct($replaceAllSpaces = true)
    {

        if (!is_bool($replaceAllSpaces)) {

            throw new Zend_Filter_Exception('Argument must be a bool');

        }

        $this->_replaceAllSpaces = $replaceAllSpaces;

    }

    /**
     * Returns whether to replace all spaces or not
     *
     * @return bool
     */
    public function getReplaceAllSpaces()
    {

        return $this->_replaceAllSpaces;

    }

    /**
     * Sets whether to replace all spaces or not
     *
     * @param bool $replaceAllSpaces
     * @throws Zend_Filter_Exception
     * @return Zend_Filter_StripSpaces
     */
    public function setReplaceAlLSpaces($replaceAllSpaces)
    {

        if (!is_bool($replaceAllSpaces)) {

            throw new Zend_Filter_Exception('Argument must be a bool');

        }

        $this->_replaceAllSpaces = $replaceAllSpaces;

        return $this;

    }

    /**
     * Returns a value replacing (all) spaces.
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {

        if ($this->_replaceAllSpaces) {

            return str_replace(' ', '', $value);

        }

        return preg_replace('/ +/', ' ', $value);

    }

}