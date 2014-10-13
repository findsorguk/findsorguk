<?php

/** Access, manipulate and delete archaeological context data.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $archaeologicalContext = new Archaeology();
 * $this->view->archaeology = $archaeologicalContext->getArchaeologyData($id);
 * ?>
 * </code>
 *
 * @author Mary Chester-Kadwell <mchester-kadwell at britishmuseum.org>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Mary Chester-Kadwell
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Db_Table
 * @subpackage Abstract
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 4 August 2014
 * @example /app/modules/database/controllers/HoardsController.php
 */
class Archaeology extends Pas_Db_Table_Abstract {

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'archaeology';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The higher level array
     * @access protected
     * @var array
     */

    /** Get archaeological context for display
     * @param integer $id
     * @return array
     */
    public function getArchaeologyData($id)  {
        $select = $this->select()
            ->from($this->_name, array(
                'id', 'hoardID', 'secuid', 'knownsite',
                'excavated', 'sitecontext', 'broadperiod',
                'period1', 'subperiod1', 'period2',
                'subperiod2', 'sitedateyear1', 'sitedateyear2',
                'sitetype', 'feature', 'featuredateyear1',
                'featuredateyear2', 'landscapetopography', 'recmethod',
                'yearexc1', 'yearexc2', 'description',
                'contextualrating', 'archiveloc', 'institution',
                'createdBy'
            ))
            ->joinLeft('hoards','archaeology.hoardID = hoards.secuid',
                array('old_hoardID' => 'hoardID'))
            ->joinLeft('periods','archaeology.period1 = periods.id',
                array('periodFrom' => 'term'))
            ->joinLeft(array('periods2' => 'periods'),'archaeology.period2 = periods2.id',
                array('periodTo' => 'term'))
            ->joinLeft('dataquality','archaeology.contextualrating = dataquality.id',
                array('archaeologicalcontextqualityrating' => 'rating'))
            ->joinLeft('recmethods','archaeology.recmethod = recmethods.id',
                array('recoverymethod' => 'method'))
            ->joinLeft('archsiteclass','archaeology.sitecontext = archsiteclass.id',
                array('siteclass'))
            ->joinLeft('archsitetype','archaeology.sitetype = archsitetype.id',
                array('sitetype'))
            ->joinLeft('archfeature','archaeology.feature = archfeature.id',
                array('feature'))
            ->joinLeft('landscapetopography','archaeology.landscapetopography = landscapetopography.id',
                array('landscapefeature' => 'feature'))
            ->where('hoards.id = ?', (int)$id);
        $select->setIntegrityCheck(false);
        return $this->getAdapter()->fetchRow($select);
    }

}
