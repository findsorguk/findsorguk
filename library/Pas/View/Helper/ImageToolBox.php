
<!-- saved from url=(0137)https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/ImageToolBox.php -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></head><body about="https://raw.githubusercontent.com/findsorguk/findsorguk/0e044981b8121339c5dc61d835be3d3e93683759/library/Pas/View/Helper/ImageToolBox.php"><pre style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/**
 * A view helper for image link toolbox generation
 *
 * An example of use:
 * &lt;code&gt;
 * &lt;?pho
 * $this-&gt;imageToolBox()
 * -&gt;setID($this-&gt;id)
 * -&gt;setCreatedBy($this-&gt;createdBy)
 * -&gt;setInstitution($this-&gt;institution);
 * ?&gt;
 * &lt;/code&gt;
 *
 * @author Daniel Pett &lt;dpett@britishmuseum.org&gt;
 * @copyright (c) 2014, Daniel Pett
 * @version 1
 * @example /app/views/scripts/partials/database/image.phtml Image view
 * @category Pas
 * @package View_Helper
 *
 *
 *
 */
class Pas_View_Helper_ImageToolBox extends Zend_View_Helper_Abstract {

    /** array of roles with no access to toolbox
     * @access protected
     * @var array
     */
    protected $_noaccess = array('public', NULL);

    /** Array of roles with restricted access
     * @access protected
     * @var array
     */
    protected $_restricted = array('member','research','hero');

    /** The recording officer role
     * @access protected
     * @var array
     */
    protected $_recorders = array('flos');

    /** Array of higher level roles
     * @access protected
     * @var array
     */
    protected $_higherLevel = array('admin','fa','treasure');

    /** An override institution
     * @access protected
     * @var string
     */
    protected $_overRide = 'PUBLIC';

    /** Id of record
     * @access protected
     * @var int
     */
    protected $_id;

    /** The institution for the record
     * @access protected
     * @var string
     */
    protected $_institution;

    /** The creator
     * @access protected
     * @var int
     */
    protected $_createdBy;

    /** Boolean can create
     * @access protected
     * @var boolean
     */
    protected $_canCreate;

    /** Get the user from the model
     * @access public
     * @return object
     */
    public function _getUser() {
        $person = new Pas_User_Details();
        return $person-&gt;getPerson();
    }

    /** Check their institution
     * @access protected
     * @return boolean
     */
    protected function _checkInstitution() {
        if ($this-&gt;getInstitution() === $this-&gt;_getUser()-&gt;institution) {
            return true;
        } else {
            return false;
        }
    }

    /** Check the creator
     * @access public
     * @return boolean
     */
    protected function _checkCreator() {
        if ($this-&gt;getCreatedBy() === $this-&gt;_getUser()-&gt;id) {
            return true;
        } else {
            return false;
        }
    }

    /** Set the id
     * @access public
     * @param int $id
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function setID($id) {
        $this-&gt;_id = $id;
        return $this;
    }

    /** Set the institution
     * @access public
     * @param string $institution
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function setInstitution($institution) {
        $this-&gt;_institution = $institution;
        return $this;
    }

    /** set created by
     * @access public
     * @param int $createdBy
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function setCreatedBy($createdBy) {
        $this-&gt;_createdBy = $createdBy;
        return $this;
    }


    /** Build the html
     * @access public
     * @return string
     */
    public function _buildHtml() {
        $html = '';
        $this-&gt;_checkParameters();
        $this-&gt;_performChecks();
        if ($this-&gt;_canCreate) {
            $paramsEdit = array(
                'module' =&gt; 'database',
                'controller' =&gt; 'images',
                'action' =&gt; 'edit',
                'id' =&gt; $this-&gt;getId()
            );
            $paramsDelete = array(
                'module' =&gt; 'database',
                'controller' =&gt; 'images',
                'action' =&gt; 'delete',
                'id' =&gt; $this-&gt;getId()
            );
            $editurl = $this-&gt;view-&gt;url($paramsEdit, 'default' ,TRUE);
            $deleteurl = $this-&gt;view-&gt;url($paramsDelete, 'default', TRUE);
            $html .= ' &lt;a class="btn btn-success" href="' . $editurl;
            $html .= '" title="Edit image"&gt;Edit&lt;/a&gt; &lt;a class="btn btn-warning" href="';
            $html .= $deleteurl . '" title="Delete this image"&gt;Delete&lt;/a&gt;';
        }
        return $html;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_ImageToolBox
     */
    public function imageToolBox() {
        return $this;
    }

    /** Perform checks for access
     * @access public
     * @return boolean
     */
    public function _performChecks() {
        if ($this-&gt;_getUser()) {
            $role = $this-&gt;_getUser()-&gt;role;
        } else {
            $role = NULL;
        }
        //If user's role is in the no access array, return false for creation
        if (in_array($role, $this-&gt;_noaccess)) {
            $this-&gt;_canCreate = false;
        }
        //If user's role is in the higher level array, return true for creation
        else if (in_array($role,$this-&gt;_higherLevel)) {
            $this-&gt;_canCreate = true;
        }
        //If user's role is in recorders group check for
        // a) user ID = creator of image
        // b) institution is a public record
        // c) institution is theirs
        else if (in_array($role,$this-&gt;_recorders)) {
            if($this-&gt;_checkCreator() ||
            $this-&gt;getInstitution() === $this-&gt;_overRide ||
            $this-&gt;_checkInstitution()) {
                $this-&gt;_canCreate = true;
            }
        }
        //If user's role is in restricted groups
        // a) check if the user's institution is theirs and they are the creator
        else if (in_array($role,$this-&gt;_restricted)) {
        if (($this-&gt;_checkCreator() &amp;&amp; $this-&gt;_checkInstitution())) {
            $this-&gt;_canCreate = true;
        }
        } else {
            $this-&gt;_canCreate = false;
        }
    }

    /** Check all parameters exist
     * @access public
     * @return boolean
     * @throws Zend_Exception
     */
    public function _checkParameters() {
        $parameters = array(
            $this-&gt;getCreatedBy(),
            $this-&gt;getInstitution(),
            $this-&gt;getId()
        );
        foreach ($parameters as $parameter) {
            if (is_null($parameter)) {
                throw new Zend_Exception('A parameter is missing');
            }
        }
        return true;
    }

    /** To string
     * @access public
     * @return string
     */
    public function __toString() {
        return $this-&gt;_buildHtml();
    }

    /** Get the id number
     * @access public
     * @return int
     */
    public function getId() {
        return $this-&gt;_id;
    }

    /** Get the institution
     * @access public
     * @return string
     */
    public function getInstitution() {
        return $this-&gt;_institution;
    }

    /** Get the creator
     * @access public
     * @return int
     */
    public function getCreatedBy() {
        return $this-&gt;_createdBy;
    }
}</pre></body></html>