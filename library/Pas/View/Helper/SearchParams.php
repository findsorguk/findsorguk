<?php

/**
 * This class is to display search params
 * @category   Pas
 * @package    View
 * @subpackage Helper
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @uses Zend_View_Helper_Abstract
 * @author Daniel Pett
 * @version 2
 * @since march 14 2012
 */
class Pas_View_Helper_SearchParams extends Zend_View_Helper_Abstract
{

    /** The cache object
     * @access protected
     * @var object
     */
    protected $_cache;

    /** The params array
     * @var arrary
     */
    protected $_params;

    protected $_format = true;

    /**
     * @return boolean
     */
    public function getFormat()
    {
        return $this->_format;
    }

    /**
     * @param boolean $format
     */
    public function setFormat($format)
    {
        $this->_format = $format;
        return $this;
    }


    /** Create the cache object
     * @access public
     *
     */
    public function getCache()
    {
        $this->_cache = Zend_Registry::get('cache');
        return $this->_cache;
    }

    /** Array of cleaned names for display as a parameter
     * @access protected
     * @var array
     */
    protected $_niceNames = array(
        'mintName' => 'Mint',
        'rulerName' => 'Ruler',
        'objectType' => 'Object type',
        'q' => 'Free text search',
        'fourFigure' => 'Four figure NGR',
        'old_findID' => 'Find number',
        'discovered' => 'Discovery year',
        'TID' => 'Treasure case number',
        'hID' => 'Hoard ID',
        'otherref' => 'other reference',
        'smrRef' => 'SMR or HER reference number',
        'typeID' => 'Medieval periodic type',
        'cciNumber' => 'Celtic coin Index number',
        'broadperiod' => 'Broad period',
        'objecttype' => 'Object type',
        'rallyID' => 'Rally known as',
        'woeid' => 'Yahoo!\'s Where on Earth ID number',
        'd' => 'Distance (in kilometres)',
        'lat' => 'latitude',
        'lon' => 'longitude',
        'bbox' => 'Bounding box co-ordinates',
        'createdBy' => 'Created by',
        'fromsubperiod' => 'Sub period from',
        'tosubperiod' => 'Sub period to',
        'periodFrom' => 'Period from',
        'periodTo' => 'Period to',
        'culture' => 'Ascribed culture',
        'otherRef' => 'Other reference',
        'elevation' => 'Height above sea level in metres',
        'geographyID' => 'Iron Age geographical region',
        'tribe' => 'Iron Age tribal association',
        'axis' => 'Die axis measurement',
        'vaType' => 'Van Arsdell number',
        'allenType' => 'Allen type',
        'ruddType' => 'Ancient British Coinage number',
        'mackType' => 'Mack Type',
        'numChiab' => 'Coin hoards of Iron Age Britain number',
        'phase_date_1' => 'Phase date 1',
        'Phase_date_2' => 'Phase date 2',
        'depositionDate' => 'Date of deposition',
        'obverseLegend' => 'Obverse inscription',
        'reverseLegend' => 'Reverse inscription',
        'obverseDescription' => 'Obverse description',
        'reverseDescription' => 'Reverse description',
        'show' => 'Show this many records per page',
        'createdAfter' => 'Created after',
        'createdBefore' => 'Created before',
        'updatedAfter' => 'Updated after',
        'updatedBefore' => 'Updated before',
        'fromdate' => 'Date from',
        'todate' => 'Date to',
        'materialTerm' => 'Primary material',
        'identifier1ID' => 'Primary identifier (obfuscated for security)',
        'identifier2ID' => 'Secondary identifier (obfuscated for security)',
        'recorderID' => 'Recorded by (obfuscated for security)',
        'decstyle' => 'Decorative style',
        'knownSite' => 'Known archaeological site',
        'featureDateYear1' => 'Feature date year from',
        'featureDateYear2' => 'Feature date year to',
        'excavatedYear1' => 'Excavated date year from',
        'excavatedYear2' => 'Excavated date year to',
        'quantityArtefacts' => 'Quantity of artefacts in hoard',
        'quantityCoins' => 'Quantity of coins in hoard',
        'quantityContainers' => 'Quantity of containers in hoard',
        'archaeologyDescription' => 'Archaeological description of site',
        'terminalReasonID' => 'Terminal dating reasoning',
        'siteDateYear1' => 'Site date from',
        'siteDateYear2' => 'Site date to',
        'terminalYear1' => 'Terminal dating from',
        'terminalYear2' => 'Terminal dating to',
        'qualityRating' => 'Rating of information',
        'legacyID' => 'Legacy hoard database ID number',
        'lastRulerID' => 'Last ruler present in hoard',
        '3D' => '3D content ready',
        'denominationName' => 'Denomination'

    );

    /** The search function to return data for
     * @access public
     * @return \SearchParams
     */
    public function searchParams()
    {
        return $this;
    }

    /** Generate the search string from parameters submitted
     * @access public
     * @param  array $params
     * @return string
     */
    public function setParams($params)
    {
        $this->_params = $this->cleanParams($params);
        return $this;

    }

    /** Get the params array
     * @access public
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }

    /** Render HtML
     * @access public
     * @param array $params
     */
    public function htmlRender($params)
    {
        $html = '<p>You searched for: ';
        $searches = array();
        if (!empty($params)) {
            $html .= '</p><ul>';

            foreach ($params as $k => $v) {
                switch ($k) {
                    case 'fromdate':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->adBc()->setDate($v) . '</li>';
                        break;
                    case 'todate':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->adBc()->setDate($v) . '</li>';
                        break;
                    case 'updated':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->NiceShortDate()->setDate($v) . '</li>';
                        break;
                    case 'created':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->NiceShortDate()->setDate($v) . '</li>';
                        break;
                    case 'updatedAfter':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->NiceShortDate()->setDate($v) . '</li>';
                        break;
                    case 'updatedBefore':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->NiceShortDate()->setDate($v) . '</li>';
                        break;
                    case 'createdBefore':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->NiceShortDate()->setDate($v) . '</li>';
                        break;
                    case 'createdAfter':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->NiceShortDate()->setDate($v) . '</li>';
                        break;
                    case 'featureDateYear2':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->adBc()->setDate($v) . '</li>';
                        break;
                    case 'featureDateYear1':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->adBc()->setDate($v) . '</li>';
                        break;
                    case 'excavatedYear1':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->adBc()->setDate($v) . '</li>';
                        break;
                    case 'excavatedYear2':
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $this->view->adBc()->setDate($v) . '</li>';
                        break;
                    default:
                        $html .= '<li>' . $this->cleanKey($k) . ': ' . $v . '</li>';
                        break;
                }
                $searches[] = $this->cleanKey($k) . ' ' . $v;

            }
            if ($this->getFormat()) {
                $this->view->headTitle('Search results from the database');
                $this->view->headMeta(implode(' - ', $searches), 'description');
                $this->view->headMeta(implode(',', $searches), 'keywords');
            }
            $html .= '</ul>';
        } else {
            $html .= 'Everything we have</p>';
        }
        if (!$this->getFormat()) {
            $html = strip_tags(implode(',', $searches));
        }
        $html .= '<hr />';
        return $html;
    }

    /** Clean the key for nice name
     * @access public
     * @param  string $string
     * @return string
     */
    public function cleanKey($string)
    {
        if (in_array($string, array_keys($this->_niceNames))) {
            $text = "$string";
            foreach ($this->_niceNames as $key => $value) {
                $text = preg_replace("|(?!<[^<>]*?)(?<![?.&])\b$key\b(?!:)(?![^<>]*?>)|msU",
                    $value, $text);
            }
        } else {
            $text = $string;
        }

        return ucfirst($text);
    }

    /** Look up the correct value and cache the results
     * @access public
     * @param  string $name The model name
     * @param  string $field The field to return
     * @param  string $value The value to lookup
     * @return string
     */
    public function getData($name, $field, $value, $idField = 'id')
    {
        $key = md5($name . $field . $value . $idField);
        if (!($this->getCache()->test($key))) {
            $model = new $name();
            $data = $model->fetchRow($model->select()->where($idField . ' = ?', $value));
            $this->getCache()->save($data);
        } else {
            $data = $this->getCache()->load($key);
        }

        if (!empty($data)) {
            $term = $data->$field;
        } else {
            $term = 'Not found in lookup table';
        }

        return $term;
    }

    /** Clean up the parameters submitted
     * @access public
     * @param  array $params The parameters submitted
     * @return string
     */
    public function cleanParams($params)
    {
        unset($params['module']);
        unset($params['controller']);
        unset($params['action']);
        unset($params['format']);
        foreach ($params as $key => $value) {
            switch ($key) {
                case 'regionID':
                    $params[$key] = $this->getData('Regions', 'region', $value);
                    break;
                case 'denomination':
                    $params[$key] = $this->getData('Denominations', 'denomination', $value);
                    break;
                case 'lastRulerID':
                    $params[$key] = $this->getData('Rulers', 'issuer', $value);
                    break;
                case 'ruler':
                    $params[$key] = $this->getData('Rulers', 'issuer', $value);
                    break;
                case 'mint':
                    $params[$key] = $this->getData('Mints', 'mint_name', $value);
                    break;
                case 'material':
                    $params[$key] = $this->getData('Materials', 'term', $value);
                    break;
                case 'hID':
                    $params[$key] = $this->getData('Hoards', 'term', $value);
                    break;
                case 'countyID':
                    $params[$key] = $this->getData('OsCounties', 'label', $value, 'osID');
                    break;
                case 'regionID':
                    $params[$key] = $this->getData('OsRegions', 'label', $value, 'osID');
                    break;
                case 'parishID':
                    $params[$key] = $this->getData('OsParishes', 'label', $value, 'osID');
                    break;
                case 'districtID':
                    $params[$key] = $this->getData('OsDistricts', 'label', $value, 'osID');
                    break;
                case 'treasure' :
                    $params[$key] = 'yes';
                    break;
                case 'rally' :
                    $params[$key] = 'yes';
                    break;
                case 'note':
                    $params[$key] = 'yes';
                    break;
                case 'hoard':
                    $params[$key] = 'yes';
                    break;
                case 'thumbnail':
                    $params[$key] = 'Only records with images please';
                    break;
                case '3D':
                    $params[$key] = 'Only records with 3D please';
                    break;
                case 'surface':
                    $params[$key] = $this->getData('SurfaceTreatments', 'term', $value);
                    break;
                case 'workflow':
                    $params[$key] = $this->getData('Workflows', 'workflowstage', $value);
                    break;
                case 'manufacture':
                    $params[$key] = $this->getData('Manufactures', 'term', $value);
                    break;
                case 'decoration':
                    $params[$key] = $this->getData('Decmethods', 'term', $value);
                    break;
                case 'category':
                    $params[$key] = $this->getData('CategoriesCoins', 'category', $value);
                    break;
                case 'reason':
                    $params[$key] = $this->getData('Findofnotereasons', 'term', $value);
                    break;
                case 'type':
                    $params[$key] = $this->getData('MedievalTypes', 'type', $value);
                    break;
                case 'rallyID':
                    $params[$key] = $this->getData('Rallies', 'rally_name', $value);
                    break;
                case 'createdBy':
                    $params[$key] = $this->getData('Users', 'fullname', $value);
                    break;
                case 'fromsubperiod':
                    $params[$key] = $this->getData('SubPeriods', 'term', $value);
                    break;
                case 'tosubperiod':
                    $params[$key] = $this->getData('SubPeriods', 'term', $value);
                    break;
                case 'periodFrom':
                    $params[$key] = $this->getData('Periods', 'term', $value);
                    break;
                case 'periodTo':
                    $params[$key] = $this->getData('Periods', 'term', $value);
                    break;
                case 'culture':
                    $params[$key] = $this->getData('Cultures', 'term', $value);
                    break;
                case 'tribe':
                    $params[$key] = $this->getData('Tribes', 'tribe', $value);
                    break;
                case 'geographyID':
                    $params[$key] = $this->getData('Geography', 'area', $value);
                    break;
                case 'axis':
                    $params[$key] = $this->getData('Dieaxes', 'die_axis_name', $value);
                    break;
                case 'moneyer':
                    $params[$key] = $this->getData('Moneyers', 'name', $value);
                    break;
                case 'reeceID':
                    $params[$key] = 'Period ' . $value . ': ' . $this->getData('Reeces', 'description', $value);
                    break;
                case 'regionID':
                    $params[$key] = $this->getData('OsRegions', 'label', $value, 'osID');
                    break;
                case 'reverse':
                    $params[$key] = $this->getData('RevTypes', 'type', $value);
                    break;
                case 'preservation':
                    $params[$key] = $this->getData('Preservations', 'term', $value);
                    break;
                case 'discovery':
                    $params[$key] = $this->getData('DiscoMethods', 'method', $value);
                    break;
                case 'decstyle':
                    $params[$key] = $this->getData('DecStyles', 'term', $value);
                    break;
                case 'complete':
                    $params[$key] = $this->getData('Completeness', 'term', $value);
                    break;
                default:
                    $params[$key] = $value;
                    break;
            }
        }

        return $params;
    }


    /** To String function
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->htmlRender($this->getParams());
    }
}