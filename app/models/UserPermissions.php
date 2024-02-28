<?php

/** Model for user permissions data
 * @copyright (c) 2023, The Trustees of the British Museum
 *
 */

class UserPermissions
{
    public const VIEW_GEO_DATA = 'ViewGeoData';
    public const VIEW_KNOWN_AS_GEO_DATA = 'ViewKnownAsGeoData';
    public const VIEW_RECORD_IDENTIFIERS = 'ViewRecordIdentifiers';
    public const VIEW_RECORD_FINDERS = 'ViewRecordFinders';
    public const VIEW_RECORD_RECORDERS = 'ViewRecordRecorders';
    public const VIEW_FULL_MAP_DETAIL = 'ViewFullMapDetail';
    protected string $userRole;

    protected array $permissions = array(
        'admin' => array(
            'ViewKnownAsGeoData' => true,
            'ViewGeoData' => true,
            'ViewRecordIdentifiers' => true,
            'ViewRecordFinders' => true,
            'ViewRecordRecorders' => true,
            'ViewFullMapDetail' => true
        ),
        'hoard' => array(
            'ViewKnownAsGeoData' => true,
            'ViewGeoData' => true,
            'ViewRecordIdentifiers' => true,
            'ViewRecordFinders' => false,
            'ViewRecordRecorders' => true,
            'ViewFullMapDetail' => true
        ),
        'fa' => array(
            'ViewKnownAsGeoData' => true,
            'ViewGeoData' => true,
            'ViewRecordIdentifiers' => true,
            'ViewRecordFinders' => true,
            'ViewRecordRecorders' => true,
            'ViewFullMapDetail' => true
        ),
        'treasure' => array(
            'ViewKnownAsGeoData' => true,
            'ViewGeoData' => true,
            'ViewRecordIdentifiers' => true,
            'ViewRecordFinders' => true,
            'ViewRecordRecorders' => true,
            'ViewFullMapDetail' => true
        ),
        'flos' => array(
            'ViewKnownAsGeoData' => true,
            'ViewGeoData' => true,
            'ViewRecordIdentifiers' => true,
            'ViewRecordFinders' => true,
            'ViewRecordRecorders' => true,
            'ViewFullMapDetail' => true
        ),
        'hero' => array(
            'ViewKnownAsGeoData' => true,
            'ViewGeoData' => true,
            'ViewRecordIdentifiers' => true,
            'ViewRecordFinders' => false,
            'ViewRecordRecorders' => true,
            'ViewFullMapDetail' => true
        ),
        'research' => array(
            'ViewKnownAsGeoData' => true,
            'ViewGeoData' => true,
            'ViewRecordIdentifiers' => false,
            'ViewRecordFinders' => false,
            'ViewRecordRecorders' => false,
            'ViewFullMapDetail' => true
        ),
        'member' => array(
            'ViewKnownAsGeoData' => false,
            'ViewGeoData' => false,
            'ViewRecordIdentifiers' => false,
            'ViewRecordFinders' => false,
            'ViewRecordRecorders' => false,
            'ViewFullMapDetail' => false
        ),
    );

    public function __construct()
    {
        $this->userRole = (new Pas_User_Details())->getRole();
    }

    public function canRole(string $permission): bool
    {
        $role = $this->userRole;
        if (in_array($permission, (new ReflectionClass($this))->getConstants())) {
            return $this->permissions[$role][$permission] ?? false;
        } else {
            throw new InvalidArgumentException('canRole function will only accept valid permissions,
            found in the class constants');
        }
    }
}
