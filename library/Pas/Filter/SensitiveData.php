<?php

/** Filter for sensitive data
 *
 */
class Pas_Filter_SensitiveData
{

    protected array $data;
    protected UserPermissions $userPermissions;

    public function __construct()
    {
        $this->userPermissions = new UserPermissions();
    }

    public function cleanData($data, $format = null, $core = null)
    {
        $this->data = $data;
        //If core is object, or call from non-solr data source
        if ($core === 'objects' || is_null($core)) {
            $this->filterSensitivePersonalData()->filterSensitiveGeoData()->filterExport($format);
        }
        return $this->data;
    }

    /** Process the data for personal sensitive fields
     *
     * @access protected
     */
    public function filterSensitivePersonalData(): Pas_Filter_SensitiveData
    {
        $finderFields = array('finder', 'finderID');
        $recorderFields = array('recorder', 'recorderID', 'createdBy', 'updatedBy', 'username', 'fullname', 'usernameUpdate', 'fullnameUpdate', 'creator');
        $identifierFields = array('identifier', 'identifierID', 'secondaryIdentifier', 'identifier1ID', 'identifier2ID');

        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_FINDERS)) {
            $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $finderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_RECORDERS)) {
            $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $recorderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_IDENTIFIERS)) {
            $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $identifierFields);
        }
        return $this;
    }

    /** Process the data for geo sensitive fields
     * @access public
     * @return Pas_Filter_SensitiveData
     */
    public function filterSensitiveGeoData(): Pas_Filter_SensitiveData
    {

        $geoDataFields = array('lat', 'lon', 'latitude', 'longitude', 'geohash', 'coordinates', 'easting', 'northing');
        $fourFigureFields = array('fourFigureLat', 'fourFigureLon');
        $discoveryMeta = array('findspotDescription', 'comments', 'landOwnerName', 'address', 'postcode', 'landOwnerID');

        $filteredGeoData = array();
        if (is_array($this->data) && !$this->userPermissions->canRole(UserPermissions::VIEW_GEO_DATA)) {
            foreach ($this->data as $record) {
                if (array_key_exists('gridref', $record)) {
                    $record['gridref'] = $record['fourFigure'];
                }

                $record = $this->filterFieldsFromData($record, $geoDataFields);
                $record = $this->filterFieldsFromData($record, $discoveryMeta);

                //If the knownas key exists and is filled in, then it needs restricting
                if (
                    !is_null($record['knownas']) &&
                    !$this->userPermissions->canRole(UserPermissions::VIEW_KNOWN_AS_GEO_DATA)
                ) {
                    $record['parish'] = $record['fourFigure'] = $record['gridref'] = 'Restricted Access';
                    $record = $this->filterFieldsFromData($record, $fourFigureFields);
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
        $formatMethod = 'filter' . ucfirst($format) . 'Export';
        if ($format != null && method_exists($this, $formatMethod)) {
            $this->$formatMethod();
        }
        return $this;
    }

    /** Filter JSON specific fields from all exports, regardless of permissions
     * @param $data
     * @return void
     */
    private function filterJsonExport()
    {
        $jsonFieldsToRemove = array(
            'address','comments','coordinates','disccircum','easting','elevation','finder','finderID',
            'findspotDescriptiom','findspotDescription','fullnameUpdate','geohash','gridref','landOwnerID',
            'landOwnerName','lat','latitude','lon','longitude','northing','postcode','username','usernameUpdate'
        );

        $this->data = $this->filterFieldsFromMultiDimensionalArray($this->data, $jsonFieldsToRemove);
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