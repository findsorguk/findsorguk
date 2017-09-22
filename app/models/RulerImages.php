<?php
/** Data model for accessing and manipulating images attached to issuers/rulers
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $images = new RulerImages();
 * $this->view->images = $images->getImages($id);
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
 * @since 22 October 2010, 17:12:34
 * @todo add caching
 * @example /app/modules/admin/controllers/NumismaticsController.php
 */

class RulerImages extends Pas_Db_Table_Abstract {

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'rulerImages';

    /** Get a list of all images attached to a ruler
     * @access public
     * @param integer $id
     * @return array
     * @todo add cache
     */
    public function getImages($id) {
        $images = $this->getAdapter();
        $select = $images->select()
                ->from($this->_name)
                ->where('rulerID = ?', (int)$id);
        return $images->fetchAll($select);
    }


    /** Get a specific image
     * @access public
     * @param integer $id
     * @return array
     */
    public function getFilename($id) {
        $images = $this->getAdapter();
        $select = $images->select()
                ->from($this->_name, array('filename'))
                ->where('id = ?', (int)$id);
        return $images->fetchAll($select);
    }

}