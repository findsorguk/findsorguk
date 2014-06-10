<?php
/**
 * Institution helper for rendering full name from abbreviation
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->institution()->setInstitution($inst);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package Pas_View_Helper
 * @example /app/modules/database/views/scripts/myscheme/myinstitution.phtml
 * @version 1
 * @license http://URL name
 * @uses viewHelper Pas_View_Helper
 *
 */
class Pas_View_Helper_Institution {

    /** The default institution
     * @access protected
     * @var string
     */
    protected $_institution = 'The Portable Antiquities Scheme';

    /** Get the institution
     * @access public
     * @return string
     */
    public function getInstitution() {
        return $this->_institution;
    }

    /** Set the institution
     * @access public
     * @return \Pas_View_Helper_Institution
     */
    public function setInstitution() {
        if (!is_null($institution)) {
        $institutions = new Institutions();
        $institution = $institutions->fetchRow(
                $institutions->select()->where('institution = ?',$institution)
                );
        if (!is_null($institution)) {
            $this->_institution = $institution->description;
        }
        }
        return $this;
    }

    /** The function
     * @access public
     * @return \Pas_View_Helper_Institution
     */
    public function institution(){
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->getInstitution();
    }
}
