<?php

/** Controller for searching for finds on database
 *
 * @todo finish module's functions and replace with solr functionality.
 * Scripts suck the big one.
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Searches
 * @uses Pas_ArrayFunctions
 * @uses Pas_Geo_MapIt_Postcode
 */
class Database_SearchController extends Pas_Controller_Action_Admin
{

    /** The basic search model
     * @access protected
     * @var \Searches
     */
    protected $_searches;

    /** The array cleaner function
     * @access protected
     * @var \Pas_ArrayFunctions
     */
    protected $_cleaner;

    /** The context array available for results
     * @access protected
     * @var array
     */
    protected $_contexts = array(
        'xml', 'rss', 'json',
        'atom', 'kml', 'georss',
        'ics', 'rdf', 'xcs',
        'csv', 'n3', 'midas',
        'geojson');

    /** Setup the contexts by action and the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_searches = new Searches();
        $this->_helper->_acl->allow('public', null);

        $this->_helper->contextSwitch()
            ->setAutoDisableLayout(true)
            ->addContext('kml', array('suffix' => 'kml'))
            ->addContext('rss', array('suffix' => 'rss'))
            ->addContext('atom', array('suffix' => 'atom'))
            ->addContext('qrcode', array('suffix' => 'qrcode'))
            ->addContext('geojson', array('suffix' => 'geojson',
                'headers' => array(
                    'Content-Type' => 'application/json')
            ))
            ->addContext('rdf', array('suffix' => 'rdf',
                'headers' => array(
                    'Content-Type' => 'application/xml')
            ))
            ->addContext('midas', array('suffix' => 'midas',
                'headers' => array(
                    'Content-Type' => 'text/xml')
            ))
            ->addActionContext('results', array(
                'json', 'xml',
                'rss', 'atom', 'kml',
                'geojson', 'qrcode'
            ))
            ->addActionContext('summaries', array('json', 'xml'))
            ->setAutoJsonSerialization(false);
        $this->_cleaner = new Pas_ArrayFunctions();
        $this->_helper->contextSwitch()->initContext();

        if (!in_array($this->_helper->contextSwitch()->getCurrentContext(),
            $this->_contexts)
        ) {
            $this->view->googleapikey = $this->_helper->config()->webservice->googlemaps->apikey;
        }
    }

    /** Process the form data
     * @access public
     * @param array $data
     * void
     */
    public function process(array $data)
    {
        $params = array_filter($data);
        $cleaned = $this->_cleaner->array_cleanup($params, array(
            'finder', 'idby', 'recordby',
            'idBy', 'recordername'
        ));

        if (array_key_exists('3D', $cleaned)) {
            if (is_null($cleaned['3D'])) {
                unset($cleaned['3D']);
            }
        }
        if (array_key_exists('thumbnail', $cleaned)) {
            if (is_null($cleaned['thumbnail'])) {
                unset($cleaned['thumbnail']);
            }
        }
        $this->getFlash()->addMessage('Your search is complete');
        $this->_helper->Redirector->gotoSimple('results', 'search', 'database', $cleaned);
    }

    /** Display the basic what/where/when page.
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $form = new SolrForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Generate the advanced search page
     * @access public
     * @return void
     */
    public function advancedAction()
    {
        $form = new AdvancedSearchForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $this->process($form->getValues());
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Generate the advanced search page
     * @access public
     * @return void
     */
    public function hoardsAction()
    {
        $form = new AdvancedHoardsSearchForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $this->process($form->getValues());
            } else {
                $form->populate($this->_request->getPost());
            }
        }
    }

    /** Display the byzantine search form
     * @access public
     * @return void
     */
    public function byzantinenumismaticsAction()
    {
        $form = new ByzantineNumismaticSearchForm();
        $this->view->byzantineform = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Display the early medieval numismatics form
     */
    public function earlymednumismaticsAction()
    {
        $form = new EarlyMedNumismaticSearchForm();
        $this->view->earlymedform = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Display the medieval numismatics page
     */
    public function mednumismaticsAction()
    {
        $form = new MedNumismaticSearchForm();
        $this->view->earlymedform = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Display the post medieval numismatics pages
     */
    public function postmednumismaticsAction()
    {
        $form = new PostMedNumismaticSearchForm();
        $this->view->earlymedform = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Display the roman numismatics pages
     * @access public
     * @return void
     */
    public function romannumismaticsAction()
    {
        $form = new RomanNumismaticSearchForm();
        $this->view->formRoman = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Display the iron age numismatics pages
     * @access public
     * @return void
     */
    public function ironagenumismaticsAction()
    {
        $form = new IronAgeNumismaticSearchForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            $this->_helper->geoFormLoaderOptions($this->getRequest()->getPost());
            if ($form->isValid($this->getRequest()->getPost())) {
                $this->process($form->getValues());
            } else {
                $this->_helper->geoFormLoaderOptions($form->getValues());
                $form->populate($form->getValues());
            }
        } else {
            $this->_helper->geoFormLoaderOptions($form->getValues());
            $form->populate($form->getValues());
        }
    }

    /** Display the greek and roman provincial pages
     * @return void
     * @access public
     */
    public function greekromanAction()
    {
        $form = new GreekRomanSearchForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Save a search
     * @access public
     * @return void
     */
    public function saveAction()
    {
        $form = new SaveSearchForm();
        $form->submit->setLabel('Save search');
        $this->view->form = $form;
        // Clean up the parameters that are to be parsed.
        $params = $this->cleanParams($this->getAllParams(), array('searchDescription', 'public', 'title'));
        $this->view->params = $params;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            $insertData = $form->getValues();
            //Serialize the search string
            $insertData['searchString'] = serialize($params);
            $saved = new SavedSearches();
            $saved->add($insertData);
            // Redirect back to that search
            $this->_helper->Redirector->gotoSimple('results', 'search', 'database', $params);
        } else {
            // Error in submission of the form
            $this->getFlash()->addMessage('There are problems with your submission.');
            //Populate with the error
            $form->populate($params);
        }
    }

    /** Function for cleaning an array efficiently
     * @access public
     * @param array $params
     * @param array $extras
     * @return array
     */
    public function cleanParams(array $params, array $extras)
    {
        $cleaner = new Pas_ArrayFunctions();
        return $cleaner->array_cleanup($params, $extras);
    }

    /** Email a search result
     * @access public
     * @return void
     */
    public function emailAction()
    {
        $user = $this->_helper->identity->getPerson();
        // Clean up the parameters that are to be parsed.
        $params = $this->cleanParams($this->getAllParams(), array(
            'searchDescription', 'public', 'title',
            'fullname', 'email', 'messageToUser'
        ));
        $this->view->params = $params;
        $form = new EmailSearchForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            unset($params['g-recaptcha-response']);
            $to[] = array(
                'email' => $form->getValue('email'),
                'name' => $form->getValue('fullname')
            );
            $from[] = array(
                'email' => $user->email,
                'name' => $user->fullname
            );
            $url = array('url' => $params);

            $assignData = array_merge($form->getValues(), $from[0], $url);
            $this->_helper->mailer($assignData, 'sendSearch', $to, null, $from);

            $this->getFlash()->addMessage('Your email has been sent to ' . $form->getValue('fullname'));
            $this->_helper->Redirector->gotoSimple('results', 'search', 'database', $params);
        } else {
            $form->populate($params);
        }
    }

    /** Display saved searches#
     * @access public
     * @return void
     */
    public function savedsearchesAction()
    {
        $allowed = array(
            'fa', 'flos', 'admin',
            'hoard', 'treasure'
        );
        if (in_array($this->getRole(), $allowed)) {
            $private = 1;
        } else {
            $private = null;
        }
        if ($this->getParam('by') === 'me') {
            $this->view->data = $this->_searches->getAllSavedSearches(
                $this->_helper->identity->getPerson()->id,
                $this->getParam('page'),
                $private
            );
        } else {
            $this->view->data = $this->_searches->getAllSavedSearches(
                null,
                $this->getParam('page'),
                $private
            );
        }
    }

    /** Display the solr form
     */
    public function solrAction()
    {
        $form = new SolrForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->process($form->getValues());
            } else {
                $form->populate($form->getValues());
            }
        }
    }


    public function postcodeAction()
    {
        $form = new PostcodeForm();
        $form->postcode->setLabel('Postcode to search on: ');
        $this->view->form = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            if ($form->isValid($form->getValues())) {
                $postcode = str_replace(' ', '', $form->getValue('postcode'));
                $area = new Pas_Geo_MapIt_Postcode();
                $area->setPartialPostCode($postcode);
                $xy = $area->get();
                $params = array(
                    'lat' => $xy->wgs84_lat,
                    'lon' => $xy->wgs84_lon,
                    'd' => $form->getValue('distance'),
                    'postcode' => $form->getValue('postcode')
                );
                $this->process($params);
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Display the index page.
     */
    public function resultsAction()
    {
        $params = $this->getAllParams();
        $search = new Pas_Solr_Handler();
        $search->setCore('objects');
        $context = $this->_helper->contextSwitch->getCurrentContext();
        $fields = new Pas_Solr_FieldGeneratorFinds();
        $fields->setContext($context);

        if ($context) {
            $params['format'] = $context;
        }
        $search->setFacets(array(
            'objectType', 'county', 'broadperiod',
            'institution', 'rulerName', 'denominationName',
            'mintName', 'materialTerm', 'workflow',
            'reeceID',
        ));

        $search->setParams($params);
        $search->execute();
        $this->view->facets = $search->processFacets();
        $this->view->paginator = $search->createPagination();
        $this->view->stats = $search->processStats();
        $this->view->results = $search->processResults();
        $this->view->server = $search->getLoadBalancerKey();
        if (array_key_exists('submit', $params)) {
            $queries = new Searches();
            $queries->insertResults(serialize($params));
        }

    }

    public function mapAction()
    {
        //The magic is in the view
    }


    public function spatialAction()
    {
        $form = new FindFilterForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $params = array(
                    'bbox' => $form->getValue('bbox'),
                    'objectType' => $form->getValue('objecttype'),
                    'broadperiod' => $form->getValue('broadperiod')
                );
                $this->process($params);
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    public function summaryAction()
    {
        $form = new CoinSummarySearchForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost() && $form->isValid($this->_request->getPost())) {
            if ($form->isValid($form->getValues())) {
                $this->processSummary($form->getValues());
            } else {
                $form->populate($$this->_request->getPost());
            }
        }
    }

    /** Process the form data
     * @access public
     * @param array $data
     * void
     */
    public function processSummary(array $data)
    {
        $params = array_filter($data);
        $cleaned = $this->_cleaner->array_cleanup($params, array(
            'finder', 'idby', 'recordby',
            'idBy', 'recordername'
        ));
        $this->getFlash()->addMessage('Your search is complete');
        $this->_helper->Redirector->gotoSimple('summaries', 'search', 'database', $cleaned);
    }

    /** Display the index page.
     */
    public function summariesAction()
    {
        $params = $this->getAllParams();
        $search = new Pas_Solr_Handler();
        $search->setCore('coinsummary');
        $search->setFields('quantity,hoardID,broadperiod,ruler,mint,geography,denomination,hoard');
        $context = $this->_helper->contextSwitch->getCurrentContext();


        if ($context) {
            $params['format'] = $context;
        }

        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->results = $search->processResults();
        if (array_key_exists('submit', $params)) {
            $queries = new Searches();
            $queries->insertResults(serialize($params));
        }
    }

    public function taggedAction()
    {
        if ($this->getParam('term', false)) {
            $params = $this->getAllParams();
            $search = new Pas_Solr_Handler();
            $search->setCore('tags');
            $context = $this->_helper->contextSwitch->getCurrentContext();
            if ($context) {
                $params['format'] = $context;
            }
            $search->setParams($params);
            $search->execute();
            $this->view->paginator = $search->createPagination();
            $this->view->results = $search->processResults();
            $this->view->server = $search->getLoadBalancerKey();

        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }
}
