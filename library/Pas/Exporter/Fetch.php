<?php

/** A class for fetching data for exporting via solr
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Exporter
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 *
 */
class Pas_Exporter_Fetch extends Pas_Exporter_Generate
{

    /** Get the data to reuse
     * @access public
     * @return void
     * @param int|null $page
     */
    public function getData($page = null)
    {
        $this->_params['show'] = $this->getMaxRows();
        if (!is_null($page)) {
            $this->_params['page'] = $page;
        }
        $this->_search = new Pas_Solr_Handler();
        $this->_search->setCore('objects');
        switch ($this->_format) {
            case 'csv':
                $this->_search->setFields($this->_csvFields);
                break;
            case 'hero':
                $this->_search->setFields($this->_heroFields);
                break;
            case 'kml':
                $this->_search->setFields($this->_kmlFields);
                break;
            case 'report':
                $this->_search->setFields($this->_reportFields);
                break;
            case 'gis':
                $this->_search->setFields($this->_gisFields);
                break;
            default:
                throw new Pas_Exporter_Exception('That format is not allowed');
                break;
        }
        $this->_search->setParams($this->_params);
        $this->_search->execute();
        $this->_results = $this->_search->_processResults();
        $paginator = $this->_search->_createPagination();
        $pages = $paginator->getPages();
        $this->_iterator = $pages->pageCount;
    }

}