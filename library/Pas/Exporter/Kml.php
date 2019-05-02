<?php

/** An extension of the base generator class to export data as KML.
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Exporter
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 *
 */
class Pas_Exporter_Kml extends Pas_Exporter_Generate
{

    /** The format name
     * @access protected
     * @var string
     */
    protected $_format = 'kml';

    /** The default kml fields
     * @access protected
     * @var array
     */
    protected $_kmlFields = array(
        'id', 'old_findID', 'description',
        'fourFigure', 'longitude', 'latitude',
        'county', 'woeid', 'district',
        'parish', 'knownas', 'thumbnail',
        'fourFigureLat', 'fourFigureLon',
	'workflow', 'createdBy'
    );

    /** The array of roles where we need to remove data
     * @access protected
     * @var array
     */
    protected $_remove = array('public', 'member', null);

    /** The array of roles where we need to sometimes restrict data
     * @access protected
     * @var array
     */
    protected $_restricted = array('research');

    /** The array of workflow with where research user has created the record then only add to the KML file
     * @access protected
     * @var array
     */
    protected $_restrictedWorkflowForResearch = array('1', '2');

    /** Get the id of the user
     * @access public
     * @return int
     */
    public function getUserID()
    {
        $user = new Pas_User_Details();
        $this->_user = $user->getPerson();

	return $this->_user->id;
    }

    /** Constructor
     * This uses the parent class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /** Get the results
     * @access public
     * @return array
     */
    public function create()
    {
        $this->_search->setFields($this->_kmlFields);
        $this->_search->setParams($this->_params);
        $this->_search->execute();
	$artefacts = $this->removeCoordinatesIfNecessary($this->_search->processResults());
        return $this->_clean($artefacts);
    }

    /** Remove coordinates if required
     * @access protected
     * @param array $data
     * @return array
     */
     private function removeCoordinatesIfNecessary($results)
     {
	$filteredData = NULL;
        foreach ($results as $artefact)
        {
            // Research users can only see their own co-ordinates if review/quarantine records and they created them.
            // Remove member and public access to detailed co-ordinates.
	    if (   (in_array($this->getRole(), $this->_restricted)
                    && (in_array($artefact['workflow'], $this->_restrictedWorkflowForResearch))
                    && ($this->getUserID() != $artefact['createdBy']) )
		|| (in_array($this->getRole(), $this->_remove)))
	    {
                unset($artefact['latitude'], $artefact['longitude']);
	    }

            if (in_array($this->getRole(), $this->_remove)
		&& !array_key_exists('knownas', $artefact)
		&& array_key_exists('fourFigureLat', $artefact))
	    {
		$artefact['latitude'] = $artefact['fourFigureLat'];
		$artefact['longitude'] = $artefact['fourFigureLon'];
	    }
	    $filteredData[] = $artefact;
	}
	return $filteredData;
     }

    /** Clean the results
     * @access protected
     * @param array $data
     * @return array
     */
    protected function _clean(array $results)
    {
        $cleanedData = NULL;
        foreach ($results as $artefact)
	{
            $record = array();
            foreach ($artefact as $k => $v)
	    {
                $trimmed = trim(strip_tags(str_replace(array('<br />'), array("\n", "\r"), utf8_decode($v))));
                $record[$k] = preg_replace( "/\r|\n/", "", $trimmed );
            }
            $cleanedData[] = $record;
        }
        return $cleanedData;
    }
}

