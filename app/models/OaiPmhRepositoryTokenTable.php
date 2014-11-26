<?php

/**
 * Retrieve and manipulate data for OAI-PMH tokens
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $tokenTable = new OaiPmhRepositoryTokenTable();
 * $resumptionToken = $tokenTable->createRow();
 * $resumptionToken->verb = $verb;
 * $resumptionToken->metadata_prefix = $metadataPrefix;
 * $resumptionToken->cursor = $cursor + 30;
 * $resumptionToken->from = $from;
 * $resumptionToken->until = $until;
 * $resumptionToken->set = $set;
 * $resumptionToken->expiration = self::unixToDb(time() + 60 * 60);
 * $resumptionToken->useragent = $this->_userAgent();
 * $resumptionToken->ipaddress = $this->_ipAddress();
 * $resumptionToken->save();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /library/Pas/OaiPmhRepository/ResponseGenerator.php
 */
class OaiPmhRepositoryTokenTable extends Pas_Db_Table_Abstract
{

    /** The table's primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table's name
     * @access protected
     * @var string
     */
    protected $_name = 'oai_pmh_repository_tokens';

    /** Get a specific token
     * @param integer $token
     * @return array
     */
    public function getToken($token)
    {
        $records = $this->getAdapter();
        $select = $records->select()
            ->from($this->_name)
            ->where($this->_name . ' .id = ?', (int)$token);
        return $records->fetchRow($select);
    }
}
