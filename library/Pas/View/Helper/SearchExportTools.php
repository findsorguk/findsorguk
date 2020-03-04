<?php

/** View helper for generating the links for exporting data
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->searchExportTools();
 * ?>
 * </code>
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @since 14/3/2012
 * @copyright Daniel Pett <dpett @ britishmuseum dot org>
 * @author dpett
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @uses viewHelper Pas_View_Helper
 * @uses viewHelper Zend_View_Helper_Url
 * @example /app/modules/database/views/scripts/search/results.phtml
 *
 */
class Pas_View_Helper_SearchExportTools extends Zend_View_Helper_Abstract
{
    /** The user object
     * @access protected
     * @var null
     */
    protected $_user = NULL;

    /** @var string Null */
    protected $_role = NULL;

    /** Roles allowed to see the download links
     *
     * @var unknown_type
     */
    protected $_allowed = array(
        'flos', 'member', 'fa',
        'admin', 'treasure', 'research',
        'hero', 'hoard'
    );

    /** Construct the user object
     *
     */
    public function __construct()
    {
        $user = new Pas_User_Details();
        $this->_user = $user->getPerson();
        if ($this->_user) {
            $this->_role = $this->_user->role;
        } else {
            $this->_role = 'public';
        }
    }

    protected function _cleanParams($params)
    {
        if (is_array($params)) {
            unset($params['controller']);
            unset($params['action']);
            unset($params['page']);
            return $params;
        } else {
            throw new Pas_Exception('Parameters have to be an array');
        }
    }

    /** Generate authenticated data
     * @access protected
     * @return string
     */
    protected function _generateHtml($quantity)
    {
        $params = Zend_Controller_Front::getInstance()->getRequest()->getUserParams();
        $params = $this->_cleanParams($params);
        $params['controller'] = 'ajax';
        $kmlRoute = array_merge($params, array('action' => 'kml'));
        $csvRoute = array_merge($params, array('action' => 'csv'));
        $herRoute = array_merge($params, array('action' => 'her'));
        $pdfRoute = array_merge($params, array('action' => 'pdf'));
        $hoardRoute = array_merge($params, array('action' => 'hoard'));
        $class = 'btn btn-small';
        $classDisabled = 'btn btn-small btn-info';
        $html = '';
        if ($quantity < 2000) {
            $html .= ' <a class="' . $class . '" href="';
            $html .= $this->view->url($kmlRoute, null, false);
            $html .= '"><i class="icon-download-alt"></i> Export all results as KML</a> ';
        } else {
            $html .= ' <a data-toggle="tooltip" title="Only available if fewer than 2000 records" class="tipme ' . $classDisabled . '" href="#"><i class="icon-download-alt"></i> KML disabled</a> ';
        }
        if ($quantity < 12000) {
            $html .= '<a class="' . $class . '" href="';
            $html .= $this->view->url($csvRoute, null, false);
            $html .= '"><i class="icon-download-alt"></i> Export as CSV</a> ';
        } else {
            $html .= ' <a data-toggle="tooltip" title="Only available if fewer than 12000 records" class="tipme ' . $classDisabled . '" href="#"><i class="icon-download-alt"></i> CSV disabled</a> ';
        }

        if (array_key_exists('objectType', $params)) {
            if ($params['objectType'] == 'HOARD') {
                $html .= '<a class="' . $class . '" href="';
                $html .= $this->view->url($hoardRoute, null, false);
                $html .= '"><i class="icon-download-alt"></i> Export Hoard specific CSV</a> ';
            }
        }
        if ($quantity < 12000) {
            if (in_array($this->_user->role, array('flos', 'admin', 'fa', 'hero', 'treasure', 'hoard'))) {
                $html .= '<a class="' . $class . '" href="';
                $html .= $this->view->url($herRoute, null, false);
                $html .= '"><i class="icon-download-alt"></i> Export for HER import</a>';
            }
        } else {
            $html .= ' <a data-toggle="tooltip" title="Only available if fewer than 12000 records" class="tipme ' . $classDisabled . '" href="#"><i class="icon-download-alt"></i> HERO disabled</a> ';
        }

	if (in_array($this->_user->role, array('flos', 'admin', 'fa'))) {
            if ($quantity < 1000) {
                $html .= ' <a class="' . $class . '" href="';
                $html .= $this->view->url($pdfRoute, null, false);
                $html .= '"><i class="icon-download-alt"></i> PDF report format</a>';
            }
            else {
                $html .= ' <a data-toggle="tooltip" title="Only available if fewer than 1000 records" class="tipme ' . $classDisabled . '" href="#"><i class="icon-download-alt"></i> PDF disabled</a> ';
            }
        }

        if ($this->_user->canRecord === '1') {
            $html .= ' <a href="' . $this->view->url(array('module' => 'database', 'controller' => 'artefacts', 'action' => 'add'),
                    null, false);
            $html .= '" class="btn btn-small btn-primary"><i class="icon-white icon-plus"></i> Add artefact</a>';
            if (in_array($this->_user->role, array('admin', 'fa', 'hoard'))) {
                $html .= ' <a href="' . $this->view->url(array('module' => 'database', 'controller' => 'hoards', 'action' => 'add'),
                        null, false);
                $html .= '" class="btn btn-small btn-primary"><i class="icon-white icon-plus"></i> Add hoard</a>';
            }
        }

        return $html;
    }

    /** Create the unauthenticated message
     * @access protected
     * @return string
     */
    protected function _generateHtmlMessage()
    {
        $html = '<a class="btn btn-info btn-small" href="';
        $html .= $this->view->url(array('module' => 'users'), null, true);
        $html .= '"><i class="icon-download icon-white"></i>Login or register so you can export data ';
        $html .= '</a>';

        return $html;
    }

    /** Create the correct html rendering based on user roles and identity
     * @access public
     * @return string
     */
    public function searchExportTools($quantity = 0)
    {
        if (in_array($this->_role, $this->_allowed)) {
            return $this->_generateHtml($quantity);
        } else {
            return $this->_generateHtmlMessage();
        }
    }

}
