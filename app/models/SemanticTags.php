<?php

/** Retrieve and manipulate data for open calais tagged content
 *
 * An example of code:
 *
 * <code>
 * <?php
 * $model = new SemanticTags();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 22 September 2011
 * @example /app/modules/database/controllers/TagsController.php
 * @todo Generate tag searching via a solr index instead.
 */
class SemanticTags extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'semanticTags';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $higherlevel = array('admin', 'flos', 'fa', 'treasure');

    /** The restricted array
     * @access protected
     * @var array
     */
    protected $restricted = array('public', 'member', 'research', 'hero');

    /** The edit test array
     * @access protected
     * @var array
     */
    protected $edittest = array('flos', 'member');

    /** Get some tagged content
     * @access public
     * @param integer $id
     * @param string $type
     * @return array
     */
    public function getTaggedContent($id, $type)
    {
        $tags = $this->getAdapter();
        $select = $tags->select()
            ->from($this->_name)
            ->where('contentID = ?', (int)$id)
            ->where('origin != ?', (string)'YahooGeo')
            ->where('contenttype = ?', ( string)$type);
        return $tags->fetchAll($select);
    }

    /** Get geotags
     * @access public
     * @param integer $id
     * @param string $type
     * @return array
     */
    public function getGeoTags($id, $type)
    {
        $tags = $this->getAdapter();
        $select = $tags->select()
            ->from($this->_name)
            ->where('contentID = ?', (int)$id)
            ->where('contenttype = ?', (string)$type)
            ->where('origin = ?', (string)'YahooGeo');
        return $tags->fetchAll($select);
    }
}