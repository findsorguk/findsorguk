<?php

/**
 * A view helper for displaying name or the Latin phrase
 *
 * A bit of a pointless view helper
 *
 * An example of use:
 * <code>
 * <?php
 * echo $this->errorCodeTitle()->setCode($code);
 * ?>
 * </code>
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see Zend_View_Helper_Abstract
 */
class Pas_View_Helper_ErrorCodeTitle extends Zend_View_Helper_Abstract
{
    public function errorCodeTitle()
    {
        return $this;
    }

    public function __toString()
    {
        return $this->getMessage($this->getCode());
    }

    protected $_code;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->_code = $code;
        return $this;
    }

    public function getStatuses()
    {
        $cache = Zend_Registry::get('cache');
        $key = md5('httpStatuses');
        if (!($cache->test($key))) {
            $ini = new Zend_Config_Ini(APPLICATION_PATH . '/config/statuses.ini', 'production');
            $codes = $ini->toArray();
            $cache->save($codes);
        } else {
            $codes = $cache->load($key);
        }
        return $codes['status'];
    }

    public function getMessage($code)
    {
        $statusCodes = $this->getStatuses();
        if(array_key_exists((string)$code, $statusCodes)){
            $message = (string) $statusCodes[$code];
        } else {
            $message = 'Unknown';
        }
        return $message;
    }
}
