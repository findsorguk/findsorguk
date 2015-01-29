<?php

/** Data model for accessing slides data
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $thumbs = new Slides;
 * $this->view->thumbs = $thumbs->getThumbnails($id);
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
 * @since 22 October 2010, 17:12:34
 * @example /app/modules/database/controllers/ArtefactsController.php
 */
class Slides extends Pas_Db_Table_Abstract
{

    /** The table name
     * @access protected
     * @var string
     */
    protected $_name = 'slides';

    /** The primary key
     * @access protected
     * @var integer
     */
    protected $_primary = 'imageID';

    /** The higher level array
     * @access protected
     * @var array
     */
    protected $_higherlevel = array('admin', 'flos', 'fa');

    /** The restricted array
     * @access protected
     * @var array
     */
    protected $_restricted = array('public', 'member');

    /** Get thumbnails for a particular find number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getThumbnails($id, $type)
    {
        if ($type === 'artefacts') {
            $joinTable = 'finds';
            $join = $joinTable . '.secuid = finds_images.find_id';
            $fields = array('old_findID', 'objecttype', 'id', 'secuid');
        } else {
            $joinTable === 'hoards';
            $join = '.secuid = finds_images.find_id';
            $fields = array('old_findID' => 'hoardID' , 'HOARD', 'id', 'secuid');
        }
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name, array(
                    'thumbnail' => 'slides.imageID',
                    'f' => 'filename',
                    'i' => 'imageID',
                    'label',
                    'createdBy'
                ))
            ->joinLeft('finds_images', 'slides.secuid = finds_images.image_id',
                array())
            ->joinLeft($joinTable, $join, $fields)
            ->joinLeft('users', 'users.id = slides.createdBy',
                array('username', 'imagedir'))
            ->where('finds.id = ?', (int)$id)
            ->order('slides.' . $this->_primary . ' ASC');
        return $thumbs->fetchAll($select);
    }


    /** Get thumbnails for a particular find number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSlides($id)
    {
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name)
            ->joinLeft('finds_images', 'slides.secuid = finds_images.image_id',
                array())
            ->joinLeft('finds', 'finds.secuid = finds_images.find_id',
                array('old_findID', 'objecttype', 'id', 'secuid'))
            ->joinLeft('users', 'slides.createdBy = users.id', array('username'))
            ->joinLeft('periods', 'slides.period = periods.id', array('broadperiod' => 'term'))
            ->where('finds.id = ?', (int)$id)
            ->order('slides.' . $this->_primary . ' ASC');
        return $thumbs->fetchAll($select);
    }

    /** Get specific thumbnails
     * @access public
     * @param integer $id
     * @return array
     */
    public function getThumb($id)
    {
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name, array('thumbnail' => 'slides.imageID'))
            ->joinLeft('finds_images', 'slides.secuid = finds_images.image_id',
                array())
            ->joinLeft('finds', 'finds.secuid = finds_images.find_id',
                array('old_findID'))
            ->where('finds.id = ?', (int)$id)
            ->limit(1);
        return $thumbs->fetchAll($select);
    }


    /** Get a specific image
     * @access public
     * @param integer $id
     * @return array
     */
    public function getImage($id)
    {
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name, array(
                'id' => 'imageID', 'filename', 'label',
                'filesize', 'county', 'period',
                'imagerights', 'institution', 'secuid',
                'created', 'createdBy', 'ccLicense',
                'type', 'mimetype'
            ))
            ->joinLeft('finds_images', 'slides.secuid = finds_images.image_id',
                array())
            ->joinLeft('finds', 'finds.secuid = finds_images.find_id',
                array('old_findID', 'broadperiod'))
            ->joinLeft('users', 'users.id = slides.createdBy',
                array('imagedir', 'fullname', 'username'))
            ->joinLeft('licenseType', 'slides.ccLicense = licenseType.id',
                array('license'))
            ->where('slides.imageID = ?', (int)$id);
        return $thumbs->fetchAll($select);
    }

    /** Get linked finds to an image
     * @access public
     * @param integer $id
     * @return array
     */
    public function getLinkedFinds($id)
    {
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name)
            ->joinLeft('finds_images', 'slides.secuid = finds_images.image_id',
                array('linkid' => 'id'))
            ->joinLeft('finds', 'finds.secuid = finds_images.find_id',
                array(
                    'old_findID',
                    'broadperiod',
                    'objecttype',
                    'findID' => 'id')
            )
            ->joinLeft('users', 'users.id = slides.createdBy',
                array('fullname', 'userid' => 'id'))
            ->where('slides.imageID = ?', (int)$id);
        return $thumbs->fetchAll($select);
    }

    /** Get linked finds to an image
     * @access public
     * @param string $secuid
     * @return array
     */
    public function getImageForLinks($secuid)
    {
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name)
            ->where('slides.secuid = ?', (string)$secuid);
        return $thumbs->fetchAll($select);
    }

    /** Get the filename for an image number
     * @access public
     * @param integer $id
     * @return array
     */
    public function getFileName($id)
    {
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name, array('f' => 'filename', 'label', 'imageID', 'secuid'))
            ->joinLeft('finds_images', 'slides.secuid = finds_images.image_id', array())
            ->joinLeft('finds', 'finds_images.find_id = finds.secuid', array('id'))
            ->joinLeft('users', 'users.id = slides.createdBy', array('imagedir'))
            ->where($this->_name . '.imageID = ?', (int)$id);
        return $thumbs->fetchAll($select);
    }

    /** Fetch deletion data
     * @access public
     * @param integer $id
     * @return array
     */
    public function fetchDelete($id)
    {
        $thumbs = $this->getAdapter();
        $select = $thumbs->select()
            ->from($this->_name, array('f' => 'filename', 'imageID', 'label'))
            ->joinLeft('finds_images', 'slides.secuid = finds_images.image_id', array())
            ->joinLeft('users', 'users.id = slides.createdBy', array('imagedir'))
            ->where($this->_name . '.imageID = ?', (int)$id);
        return $thumbs->fetchAll($select);
    }

    /** Solr data for updating the system
     * @access public
     * @param integer $id
     * @return array
     */
    public function getSolrData($id, $type = NULL)
    {
        $slides = $this->getAdapter();
        $select = $slides->select()
            ->from($this->_name, array(
                'identifier' => new Zend_Db_Expr("CONCAT('images-',imageID)"),
                'id' => 'imageID',
                'title' => 'label',
                'filename',
                'keywords',
                'createdBy',
                'updated',
                'created',
                'license' => 'ccLicense'
            ))
            ->joinLeft('periods', $this->_name . '.period = periods.id',
                array('broadperiod' => 'term'))
            ->joinLeft('finds_images', 'finds_images.image_id = slides.secuid',
                array())
            ->joinLeft('finds', 'finds_images.find_id = finds.secuid',
                array('old_findID', 'findID' => 'finds.id'))
            ->joinLeft('findspots', 'finds.secuid = findspots.findID',
                array(
                    'woeid',
                    'latitude' => 'declat',
                    'longitude' => 'declong',
                    'coordinates' => new Zend_Db_Expr("CONCAT( findspots.declat,  ',', findspots.declong )"),
                    'county'
                ))
            ->joinLeft('users', 'slides.createdBy = users.id',
                array('imagedir', 'fullname'))
            ->joinLeft('licenseType', 'slides.ccLicense = licenseType.id',
                array('licenseAcronym' => 'acronym', 'license' => 'flickrID'))
            ->where('slides.imageID = ?', (int)$id);
        return $slides->fetchAll($select);
    }

    /** Add and resize images
     * @access public
     * @param array $imageData
     */
    public function addAndResize($imageData)
    {
        // Loop through the array of objects to add
        foreach ($imageData as $data) {

            $finds = new Finds();
            $findID = $finds->fetchRow($finds->select()->where('id = ?', $data->findID))->secuid;
            // Create the image data array
            $images = array(
                'secuid' => $data->secuid,
                'filesize' => $data->size,
                'filename' => $data->name,
                'mimetype' => $data->mimetype,
                'filecreated' => $this->timeCreation(),
                'institution' => $this->getInstitution(),
                'created' => $this->timeCreation(),
                'createdBy' => $this->getUserNumber()
            );

            // Create the linking data
            $linkData = array(
                'find_id' => $findID,
                'image_id' => $data->secuid,
                'created' => $this->timeCreation(),
                'createdBy' => $this->getUserNumber(),
                'secuid' => $this->generateSecuId()
            );
            // Insert the image data to slides table
            $slideID = parent::insert($images);

            // Create the links in the link table
            $links = new FindsImages();
            // Insert that data
            $links->insert($linkData);

            // Now process the images
            $processor = new Pas_Image_Magick();
            // Set the path
            $processor->setImage($data->path);
            // Set the thumbnail image number from slide insertion - must be an integer
            $processor->setImageNumber((int)$slideID);
            // Resize loop
            $processor->resize();
            return $slideID;
        }
    }
}