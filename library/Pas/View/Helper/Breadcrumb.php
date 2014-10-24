<?php
/**
 * This class is to display the breadcrumbs
 * Load of rubbish, needs a rewrite and to use reflection maybe
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @since September 13 2008
 * @todo change the class to use zend_navigation
 * @uses Zend_Controller_Front
 * 
*/
class Pas_View_Helper_Breadcrumb extends Zend_View_Helper_Abstract
{
    /** The module
     * @access protected
     * @var string
     */
    protected $_module;

    /** The front controller
     * @access protected
     * @var object
     */
    protected $_front;

    /** The controller
     * @access protected
     * @var string
     */
    protected $_controller;

    /** The action
     * @access protected
     * @var string
     */
    protected $_action;

    /** The separator to use
     * @access protected
     * @var string
     */
    protected $_separator = ' &raquo; ';

    /** The base url
     * @access protected
     * @var string
     */
    protected $_url;

    /** Separator for URL
     * @access public
     * @var string
     */
    protected $_slash = '/';

    /** Get the front controller
     * @access public
     * @return object
     */
    public function getFront() {
        $this->_front = Zend_Controller_Front::getInstance()->getRequest();
        return $this->_front;
    }

    /** Get the action
     * @access public
     * @return string
     */
    public function getAction() {
        $this->_action = $this->getFront()->getActionName();
        return $this->_action;
    }

    /** Get the separator
     * @access public
     * @return string
     *
     */
    public function getSeparator() {
        return $this->_separator;
    }

    /** Get the base url
     * @access public
     * @return string
     */
    public function getUrl() {
        $this->_url = $this->view->serverUrl() . $this->view->baseUrl() . '/';
        return $this->_url;
    }

    /** Get the module
     * @access public
     * @return srring
     */
    public function getModule() {
        $this->_module = $this->getFront()->getModuleName();
        return $this->_module;
    }

    /** Get the controller
     * @access public
     * @return string
     */
    public function getController() {
        $this->_controller = $this->getFront()->getControllerName();
        return $this->_controller;
    }

    /** The view helper class
     * @access public
     * @return \Pas_View_Helper_Breadcrumb
     */
    public function breadcrumb() {
        return $this;
    }

    /** A switch to get the nice name for the module
     * @access public
     * @return string
     */
    public function _switchModule()
    {
        switch ($this->getModule()) {
            case 'getinvolved':
                $clean = 'Getting involved';
                break;
            case 'admin':
                $clean = 'Administration centre';
                break;
               case 'conservation':
                $clean = 'Conservation advice';
                break;
            case 'research':
                $clean = 'Research';
                break;
            case 'treasure':
                $clean = 'Treasure Act';
                break;
            case 'news':
                $clean = 'news &amp; reports';
                break;
            case 'events':
                $clean = 'Events';
                break;
            case 'info':
                $clean = 'Site information';
                break;
            case 'romancoins':
                $clean = 'Roman Numismatic guide';
                break;
            case 'greekromancoins':
                $clean = 'Greek and Roman Provincial Numismatic guide';
                break;
             case 'api':
                 $clean = 'Application programming interface';
                 break;
            case 'bronzeage':
                $clean = 'Bronze Age object guide';
                break;
            case 'staffshoardsymposium':
                $clean  = 'Staffordshire Hoard Symposium';
                break;
            case 'database':
                $clean = 'Finds database';
                break;
            case 'medievalcoins':
                $clean = 'Medieval coin guide';
                break;
            case 'ironagecoins':
                $clean = 'Iron Age coin guide';
                break;
            case 'earlymedievalcoins':
                $clean = 'Early Medieval coin guide';
                break;
            case 'greekandromancoins':
                $clean = 'Greek &amp; Roman Provincial coin guide';
                break;
            case 'byzantinecoins':
                $clean = 'Byzantine coin guide';
                break;
            case 'postmedievalcoins':
                $clean = 'Post Medieval coin guide';
                break;
            case 'getinvolved':
                $clean = 'Get involved';
                break;
            case 'contacts':
                $clean = 'Scheme contacts';
                break;
            case 'events':
                $clean = 'Scheme events';
                break;
            case 'secrettreasures':
                $clean = 'Britain\'s Secret Treasures';
                break;
            default:
                $clean = ucfirst($this->getModule());
                break;
            }

        return $clean;
    }

    /** A function to get the nice name for the controller
     * @access public
     * @return string
     */
    public function _switchController()
    {
        switch ($this->getController()) {
            case 'error':
                $clean = 'Error manager';
                break;
            case 'users':
                $clean = 'Users\' section';
                break;
            case 'admin':
                $clean = 'Site Administration';
                break;
            case 'britishmuseum':
                $clean = 'British Museum events';
                break;
            case 'datatransfer':
                $clean = 'Data transfer';
                break;
            case 'info':
                $clean = 'Event information';
                break;
            case 'foi':
                $clean = 'Freedom of Information Act';
                break;
            case 'her':
                $clean = 'Historic Enviroment Signatories';
                break;
            case 'myscheme':
                $clean = 'My scheme';
                break;
            case 'vanarsdelltypes':
                $clean = 'Van Arsdell Types';
                break;
            case 'smr':
                $clean = 'Scheduled Monuments';
                break;
            case 'osdata':
                $clean = 'Ordnance Survery Open Data';
                break;
            case 'theyworkforyou':
                $clean = 'Data from TheyWorkForYou';
                break;
            default:
                $clean = ucfirst($this->getController());
                break;
        }
        return $clean;
    }

    /** A function to get the nice name for an action
     * @access public
     * @return string
     */
    public function _switchAction() {
        switch ($this->getAction()) {
            case 'mapsearchresults':
                $clean = 'Map search results';
                break;
            case 'countystats':
                $clean = 'County statistics';
                break;
            case 'regionalstats':
                $clean = 'Regional statistics';
                break;
            case 'institutionstats':
                $clean = 'Institutional statistics';
                break;
            case 'numismaticsearch':
                $clean = 'Numismatic search';
                break;
            case 'profile':
                $clean = 'Profile details';
                break;
            case 'add':
                $clean = 'Create new';
                break;
            case 'myresearch':
                $clean = 'My research agendas';
                break;
            case 'myinstitution':
                $clean = 'My institution\'s finds';
                break;
            case 'forgot':
                $clean = 'Reset forgotten password';
                break;
            case 'login':
                $clean = 'Login';
                break;
            case 'advanced':
                $clean = 'Advanced search interface';
                break;
            case 'basicsearch':
                $clean = 'Basic what/where/when search interface';
                break;
            case 'searchresults':
                $clean = 'Search results';
                break;
            case 'organisations':
                $clean = 'Registered Organisations';
                break;
            case 'addfindspot':
                $clean = 'Add a findspot';
                break;
            case 'editfindspot':
                $clean = 'Edit findspot';
                break;
            case 'editpublication':
                $clean = 'Edit a publication\'s details';
                break;
            case 'publication':
                $clean = 'Publication\'s details';
                break;
            case 'addromancoin':
                $clean = 'Add Roman numismatic data';
                break;
            case 'romannumismatics':
                $clean = 'Roman numismatic search';
                break;
            case 'record':
                $clean = 'Object/coin record';
                break;
            case 'emperorbios':
                $clean = 'Emperor biographies';
                break;
            case 'postmednumismatics':
                $clean ='Post Medieval numismatic search';
                break;
            case 'project':
                $clean = 'Project details';
                break;
            case 'hers':
                $clean = 'HER offices signed up';
                break;
            case 'ruler':
                $clean = 'Ruler details';
                break;
            case 'error':
                $clean = 'Error details';
                break;
            case 'errorreport':
                $clean = 'Submit an error';
                break;
            case 'oneto50k':
                $clean = 'One to 50K entry';
                break;
            case 'myfinds':
                $clean = 'Finds I have recorded';
                break;
            case 'myimages':
                $clean = 'Images I have added';
                break;
            case 'mp':
                $clean = 'Member of Parliament';
                break;
            case 'recordedbyflos':
                $clean = 'Recorded by an FLO';
                break;
            case 'accountproblem':
                $clean = 'Problem with your account';
                break;
            case 'inaset':
                $clean = 'In a set';
                break;
            case 'savedsearches':
                $clean = 'Saved searches';
                break;
            default:
                $clean = ucfirst($this->getAction());
                break;
        }
        return $clean;
    }

    /** function to build the html
     * @access public
     * @return string
     */
    public function html() {
        $html = '';
        // Get our url and create a home crumb
        $homeLink = '<a href="' . $this->getUrl() . '" title="Scheme website home page">Home</a>';
        // Start crumbs
        $html .= $homeLink . $this->getSeparator();

        // If our module is default
        if ($this->getModule() == 'default') {
            if ($this->getAction() == 'index') {
                $html .= $this->_switchModule();
            } else {
                $html .= ' <a href="';
                $html .= $this->getUrl();
                $html .= $this->getController();
                $html .= '" title="Return to ';
                $html .= $this->_switchModule();
                $html .= ' section">';
                $html .= $this->_switchModule();
                $html .= '</a> ';
                $html .= $this->getSeparator();
                $html .= $this->_switchAction();
            }
        } else {
            // Non Default Module
            if ($this->getController() == 'index' && $this->getAction() == 'index') {
                $html .= $this->_switchModule();
            } else {
                $html .= '<a href="';
                $html .= $this->getUrl();
                $html .= $this->getModule();
                $html .= '" title="Return to';
                $html .= $this->_switchController();
                $html .= ' home">';
                $html .= $this->_switchModule();
                $html .= '</a> &raquo; ';
                if ($this->getAction() == 'index') {
                    $html .= $this->_switchController();
                } else {
                    $html .= ' <a href="';
                    $html .= $this->getUrl();
                    $html .= $this->getModule();
                    $html .= '/';
                    $html .= $this->getController();
                    $html .= '" title="Return to ';
                    $html .= $this->_switchController();
                    $html .= ' home">';
                    $html .= $this->_switchController();
                    $html .= '</a>';
                    $html .=  $this->getSeparator();
                    $html .=  $this->_switchAction();
                }
            }

        }
        return $html;
    }

    /** Magic to string function
     * @access public
     * @return string
     */
    public function __toString() {
        return $this->html();
    }
}