<?php
/**
 * Model for pulling lists of accredited museums from the database.
 *
 * This information was supplied by Arts Council England.
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $model = new AccreditedMuseums();
 * $museums = $model->mapMuseums();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Db_Table
 * @subpackage Abstract
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/contacts/controllers/AccreditedmuseumsController.php
 * @uses Zend_Cache
 * @uses Zend_Paginator
 */

class AccreditedMuseums extends Pas_Db_Table_Abstract {

    /** The primary key for the table
     * @access protected
     * @var int
     */
    protected $_primary = 'id';

    /** The table name
     * @access protected
     * @var type
     */
    protected $_name = 'accreditedMuseums';


    /** Get list of all acronyms and paginator them
     * @access public
     * @return array
     * @param  array $params sent via controller
     */
    public function listMuseums( array $params)	{
        $museums = $this->getAdapter();
        $select = $museums->select()
            ->from($this->_name, array(
                'museumName', 'lat', 'lon',
                'id', 'accreditedNumber'))
            ->joinLeft('accreditedRegions','accreditedRegions.id = '
                . $this->_name . '.area', array('regionName'))
            ->joinLeft('accreditedStatus','accreditedStatus.id = '
                . $this->_name . '.status', array('status'));
        $paginator = Zend_Paginator::factory($select);
        $paginator->setItemCountPerPage(20)
            ->setPageRange(10)
            ->setCache( $this->_cache );
        if(isset($params['page']) && ($params['page'] != "")) {
            $paginator->setCurrentPageNumber($params['page']);
        }
        return $paginator;
    }

    /** Fetch data for a list of museums used for the map
     * @access public
     * @return array
     * @example path description
     */
    public function mapMuseums(){
        $museums = $this->getAdapter();
        $select = $museums->select()
            ->from($this->_name, array('museumName', 'lat', 'lon',
                'id', 'accreditedNumber'));
        return $museums->fetchAll($select);
    }
}