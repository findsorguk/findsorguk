<?php

/** Filter for sensitive data
 *
 */
class Pas_Filter_SensitiveData
{
    protected array $data;
    protected UserPermissions $userPermissions;
    protected array $finderFields = array('finder', 'finderID');
    protected array $recorderFields = array(
        'recorder', 'recorderID', 'createdBy', 'updatedBy', 'username', 'fullname', 'usernameUpdate', 'fullnameUpdate',
        'creator'
    );
    protected array $identifierFields = array(
        'identifier', 'identifierID', 'secondaryIdentifier', 'identifier1ID',
        'identifier2ID'
    );
    protected array $geoDataFields = array(
        'lat', 'lon', 'latitude', 'longitude', 'geohash', 'coordinates', 'easting', 'northing'
    );
    protected array $fourFigureFields = array('fourFigureLat', 'fourFigureLon');
    protected array $discoveryMeta = array(
        'findspotDescription', 'comments', 'landOwnerName', 'address', 'postcode', 'landOwnerID'
    );

    public function __construct()
    {
        $this->userPermissions = new UserPermissions();
    }

    public function cleanData($data, $format = null, $core = null)
    {
        $this->data = $data;

        //Filter GeoData for all data formats
        $this->filterSensitiveGeoData();

        //Only preform further filtering on exports
        if (($core !== 'objects' && !is_null($core))  || $format === 'search') {
            return $this->data;
        }

        $this->filterSensitivePersonalData()->filterExport($format);

        return $this->data;
    }

    /** Process the data for personal sensitive fields
     *
     * @access protected
     */
    public function filterSensitivePersonalData(): Pas_Filter_SensitiveData
    {
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_FINDERS)) {
            $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $this->finderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_RECORDERS)) {
            $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $this->recorderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_IDENTIFIERS)) {
            $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $this->identifierFields);
        }
        return $this;
    }

    /** Process the data for geo sensitive fields
     * @access public
     * @return Pas_Filter_SensitiveData
     */
    public function filterSensitiveGeoData(): Pas_Filter_SensitiveData
    {
        $filteredGeoData = array();
        if (is_array($this->data) && !$this->userPermissions->canRole(UserPermissions::VIEW_GEO_DATA)) {
            foreach ($this->data as $record) {
                if (array_key_exists('gridref', $record)) {
                    $record['gridref'] = $record['fourFigure'];
                }

                $record = $this->filterFieldsFromData($record, $this->geoDataFields);
                $record = $this->filterFieldsFromData($record, $this->discoveryMeta);

                //If the knownas key exists and is filled in, then it needs restricting
                if (
                    !is_null($record['knownas']) &&
                    !$this->userPermissions->canRole(UserPermissions::VIEW_KNOWN_AS_GEO_DATA)
                ) {
                    $record['parish'] = $record['fourFigure'] = $record['gridref'] = 'Restricted Access';
                    $record = $this->filterFieldsFromData($record, $this->fourFigureFields);
                }
                $filteredGeoData[] = $record;
            }
            $this->data = $filteredGeoData;
        }
        return $this;
    }

    /** Call export specific method to filter data if it exists
     * @param string|null $format
     * @return $this
     */
    public function filterExport(?string $format): Pas_Filter_SensitiveData
    {
        $formatsToFilter = array('json', 'geojson', 'kml', 'xml');
        $fieldsToFilter = array(
            'address','comments','coordinates','disccircum','easting','elevation','finder','finderID',
            'findspotDescription','fullnameUpdate','geohash','gridref','landOwnerID','landOwnerName','lat','lon',
            'northing','postcode','usernameUpdate','finder','fullnameUpdate','latitude','longitude','username',
            'usernameUpdate','username'
        );

        if (in_array(strtolower($format), $formatsToFilter)) {
            $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $fieldsToFilter);
        }
        return $this;
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
