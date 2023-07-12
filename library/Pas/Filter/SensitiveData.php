<?php

/** Filter for sensitive data
 *
 */
class Pas_Filter_SensitiveData
{

    protected UserPermissions $userPermissions;

    public function __construct()
    {
        $this->userPermissions = new UserPermissions();
    }

    public function cleanData($data)
    {
        return $this->filterSensitivePersonalData(
            $this->filterSensitiveGeoData($data)
        );
    }

    /** Process the data for personal sensitive fields
     *
     * @access protected
     *
     * @param array $data
     * @param string $role
     *
     * @return array
     */
    public function filterSensitivePersonalData($data)
    {
        $finderFields = array('finder', 'finderID');
        $recorderFields = array('recorder', 'recorderID', 'createdBy', 'updatedBy', 'username', 'fullname', 'usernameUpdate', 'fullnameUpdate', 'creator');
        $identifierFields = array('identifier', 'identifierID', 'secondaryIdentifier', 'identifier1ID', 'identifier2ID');

        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_FINDERS)) {
            $data = $this->filterFieldsFromMultiDimensionalArray($data, $finderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_RECORDERS)) {
            $data = $this->filterFieldsFromMultiDimensionalArray($data, $recorderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_IDENTIFIERS)) {
            $data = $this->filterFieldsFromMultiDimensionalArray($data, $identifierFields);
        }
        return $data;
    }

    /** Process the data for geo sensitive fields
     * @access public
     * @param array $data
     */
    public function filterSensitiveGeoData($data)
    {

        $geoDataFields = array('lat', 'lon', 'latitude', 'longitude', 'geohash', 'coordinates', 'easting', 'northing');
        $fourFigureFields = array('fourFigureLat', 'fourFigureLon');
        $discoveryMeta = array('findspotDescription', 'comments', 'landOwnerName', 'address', 'postcode', 'landOwnerID');

        $filteredGeoData = array();
        if (is_array($data) && !$this->userPermissions->canRole(UserPermissions::VIEW_GEO_DATA)) {
            foreach ($data as $record) {
                if (array_key_exists('gridref', $record)) {
                    $record['gridref'] = $record['fourFigure'];
                }

                $record = $this->filterFieldsFromData($record, $geoDataFields);
                $record = $this->filterFieldsFromData($record, $discoveryMeta);

                //If the knownas key exists and is filled in, then it needs restricting
                if (
                    !is_null($record['knownas']) &&
                    $this->userPermissions->canRole(UserPermissions::VIEW_KNOWN_AS_GEO_DATA)
                ) {
                    $record['parish'] = $record['fourFigure'] = $record['gridref'] = 'Restricted Access';
                    $record = $this->filterFieldsFromData($record, $fourFigureFields);
                }
                $filteredGeoData[] = $record;
            }
            return $filteredGeoData;
        } else {
            return $data;
        }
    }

    protected function filterFieldsFromMultiDimensionalArray(array $data, $filterFields): array
    {
        $filteredData = array();
        foreach ($data as $record) {
            $filteredData[] = $this->filterFieldsFromData($record, $filterFields);
        }

        return $filteredData;
    }

    protected function filterFieldsFromData(array $data, $filterFields): array
    {
        return  array_diff_key(
            $data,
            array_flip($filterFields)
        );
    }

}