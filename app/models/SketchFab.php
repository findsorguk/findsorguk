<?php

/**
 * Model for interacting with SketchFab table
 *
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/forms/IronAgeCoinForm.php
 */
class SketchFab extends Pas_Db_Table_Abstract
{

    /** The name of the database table
     * @access protected
     * @var string
     */
    protected $_name = 'sketchFab';

    /** The default primary key
     * @access public
     * @var int
     */
    protected $_primary = 'id';

    /** Get sketchfab models attached to record
     * @access public
     * @param int $id
     */
    public function getModels($id)
    {
        $models = $this->getAdapter();
        $select = $models->select()
            ->from($this->_name, array('modelID'))
            ->joinLeft('finds', 'finds.secuid = sketchFab.findID', array())
            ->where('finds.id = ?', (string)$id);
        return $models->fetchAll($select);
    }

}