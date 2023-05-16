<?php

/** Model for user permissions data
 * @copyright (c) 2023, The Trustees of the British Museum
 *
 */

class UserPermissions
{
    protected string $userRole;

    /**  The array of roles that can view geodata
     *
     * @access public
     * @var array
     */
    protected array $rolesAllowedGeoData
        = array(
            'fa',
            'flos',
            'admin',
            'treasure',
            'research',
            'hero',
            'hoard'
        );

    /** The array of people who can see the personal data set.
     *
     * @access public
     * @var array
     */
    protected array $rolesAllowedPersonalData
        = array(
            'fa',
            'flos',
            'admin',
            'treasure'
        );

    /** The array of people who can see the KnownAs data set.
     *
     * @access public
     * @var array
     */
    protected array $rolesAllowedKnownAs
        = array(
            'research',
            'hero',
            'flos',
            'treasure',
            'fa',
            'hoard',
            'admin'
        );

    public function __construct()
    {
        $this->userRole = (new Pas_User_Details())->getRole();
    }

    public function allowedAccessGeoData(): bool
    {
        return in_array($this->userRole, $this->rolesAllowedGeoData);
    }

    public function allowedAccessKnownAsGeoData()
    {
        return in_array($this->userRole, $this->rolesAllowedKnownAs);
    }

    public function allowedAccessPersonalFinderData(): bool
    {
        return in_array($this->userRole, $this->rolesAllowedPersonalData);
    }
}
