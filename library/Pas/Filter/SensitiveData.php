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
        $recorderFields = array('recorder', 'recorderID', 'createdBy');
        $identifierFields = array('identifier', 'identifierID', 'secondaryIdentifier', 'identifier2ID');

        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_FINDERS)) {
            $data = $this->filterFieldsFromData($data, $finderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_RECORDERS)) {
            $data = $this->filterFieldsFromData($data, $recorderFields);
        }
        if (!$this->userPermissions->canRole(UserPermissions::VIEW_RECORD_IDENTIFIERS)) {
            $data = $this->filterFieldsFromData($data, $identifierFields);
        }
        return $data;
    }

    /** Process the data for geo sensitive fields
     * @access public
     * @param array $data
     */
    public function filterSensitiveGeoData($data)
    {
        $filteredGeoData = array();
        $canRoleViewGeoData = $this->userPermissions->canRole(UserPermissions::VIEW_KNOWN_AS_GEO_DATA);
        if (is_array($data) && !$this->userPermissions->canRole(UserPermissions::VIEW_GEO_DATA)) {
            foreach ($data as $record) {
                if (array_key_exists('gridref', $record)) {
                    $record['gridref'] = $record['fourFigure'];
                }

                //If the knownas key exists and is filled in, then it needs restricting
                if (!is_null($record['knownas']) && $canRoleViewGeoData) {
                    $record['parish'] = $record['fourFigure'] = $record['gridref'] = 'Restricted Access';

                    // Unset the fourfigure lat/lon
                    unset($record['fourFigureLat']);
                    unset($record['fourFigureLon']);
                }
                $filteredGeoData[] = $record;
            }
            return $filteredGeoData;
        } else {
            return $data;
        }
    }

    protected function filterFieldsFromData(array $data, $filterFields): array
    {
        $filteredData = array();
        foreach ($data as $record) {
            $filteredData[] = array_diff_key(
                $record,
                array_flip($filterFields)
            );
        }
        return $filteredData;
    }

}