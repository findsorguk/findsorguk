<?php

/** Access, manipulate and delete hoards data.
 *
 * An example of use:
 *
 * <code>
 * <?php
 *
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 4 August 2014
 * @example /app/modules/database/controllers/HoardsController.php
 */
class Hoards extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'hoards';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin','flos','fa','hero','treasure');

    /** The parish stop access
     * @access public
     * @var array
     */
    protected $_parishStop = array('admin','flos','fa','hero','treasure','research');

    /** The restricted access array
     * @access protected
     * @var array
     */
    protected $_restricted = array(null, 'public','member','research');

    /** The error code thrown by the MySQL database when attempting to enter
     * a duplicate value into a unique field
     *
     */
    const DUPLICATE_UNIQUE_VALUE_ERROR_CODE = 23000;

    /** Get data for individual hoard record from multiple tables
     * @access public
     * @param integer $hoardId
     * @param string $role
     * @return array
     */
    public function getIndividualHoard($hoardId, $role){

    }


}