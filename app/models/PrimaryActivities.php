<?php
/** Retrieve and manipulate data from the Primary activity table
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $activities = new PrimaryActivities();
 * $activities_options = $activities->getTerms();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/forms/OrganisationFilterForm.php
*/
class PrimaryActivities extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'primaryactivities';

    /** The primary key
     * @access protected
     * @var type
     */
    protected $_primary = 'id';

    /** Get all valid terms
     * @access public
     * @return array
     */
    public function getTerms() {
        $select = $this->select()
                ->from($this->_name, array('id', 'term'))
                ->order('term')
                ->where('valid = ?',(int)1);
        return $this->getAdapter()->fetchPairs($select);
    }

    /** Get all valid activities
     * @access public
     * @return array
     */
    public function getActivitiesList() {
        $acts = $this->getAdapter();
        $select = $acts->select()
                ->from($this->_name)
                ->order(array('term'))
                ->where('valid = ?',(int)1);
        return $acts->fetchAll($select);
    }

    /** Get all activities for admin console
     * @access public
     * @return array
     */
    public function getActivitiesListAdmin() {
        $acts = $this->getAdapter();
        $select = $acts->select()
                ->from($this->_name)
                ->joinLeft('users','users.id = ' . $this->_name
                        . '.createdBy',array('fullname'))
                ->joinLeft('users','users_2.id = ' . $this->_name
                        . '.updatedBy',array('fn' => 'fullname'))
                ->order(array('term'));
        return $acts->fetchAll($select);
    }


    /** Get  activity details by ID
     * @access public
     * @param integer $id
     * @return array
     */
    public function getActivityDetails($id) {
        $acts = $this->getAdapter();
        $select = $acts->select()
                ->from($this->_name)
                ->where('id =?',(int)$id)
                ->where('valid = ?',(int)1);
        return $acts->fetchAll($select);
    }

    /** Get all valid activities as a count
     * @access public
     * @param integer $id
     * @return array
     */
    public function getActivityPersonCounts($id) {
        $acts = $this->getAdapter();
        $select = $acts->select()
                ->from($this->_name,array())
                ->joinLeft('people','people.primary_activity = '
                        . $this->_name . '.id',
                        array('c' => 'COUNT(' . $this->_name . '.id)'))
                ->where($this->_name . '.id =?',(int)$id)
                ->where('valid = ?',(int)1)
                ->group($this->_name .  '.id');
        return $acts->fetchAll($select);
    }
}