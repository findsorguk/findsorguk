<?php

/**
 * View helper for expanding section titles in admin backend
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->sectionTitle()->setTitle('api');
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @example /app/views/scripts/partials/admin/contentHelpTable.phtml
 * @category Pas
 * @package Pas_View_Helper
 * @version 1
 * @copyright (c) 2014, Daniel Pett
 */
class Pas_View_Helper_SectionTitle
{

    /** The section title default
     * @access protected
     * @var string
     */
    protected $_title = 'Default title';

    /** Get the title
     * @access public
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /** Set the title
     * @access public
     * @param string $title
     * @return \Pas_View_Helper_SectionTitle
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_SectionTitle
     */
    public function sectionTitle()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->getSection($this->getTitle());
    }

    /** Get the section
     * @access public
     * @param string $title
     * @return string
     */
    public function getSection($title)
    {
        switch ($title) {
            case 'ironagecoins':
                $sec = 'Iron Age coin guide';
                break;
            case 'api':
                $sec = 'API documentation';
                break;
            case 'medievalcoins':
                $sec = 'Medieval coin guide';
                break;
            case 'earlymedievalcoins':
                $sec = 'Early Medieval coin guide';
                break;
            case 'postmedievalcoins':
                $sec = 'Post Medieval coin guide';
                break;
            case 'news':
                $sec = 'News';
                break;
            case 'events':
                $sec = 'Events';
                break;
            case 'treasure':
                $sec = 'Treasure Act';
                break;
            case 'conservation':
                $sec = 'Conservation guide';
                break;
            case 'romancoins':
                $sec = 'Roman coin guide';
                break;
            case 'getinvolved':
                $sec = 'Get involved';
                break;
            case 'byzantinecoins':
                $sec = 'Byzantine coin guide';
                break;
            case 'greekromancoins':
                $sec = 'Greek and Roman coin guide';
                break;
            case 'info':
                $sec = 'Site information';
                break;
            case 'reviews':
                $sec = 'Scheme reviews';
                break;
            case 'reports':
                $sec = 'Annual reports';
                break;
            case 'research':
                $sec = 'Research';
                break;
            case 'datatransfer':
                $sec = 'Data transfer';
                break;
            case 'help':
                $sec = 'Site help';
                break;
            case 'databasehelp':
                $sec = 'Database help';
                break;
            case 'publications':
                $sec = 'Scheme publications';
                break;
            case 'staffs':
                $sec = 'Staffs Hoard symposium';
                break;
            case 'bronzeage':
                $sec = 'Bronze Age object guide';
                break;
            case 'treasure':
                $sec = 'Treasure';
                break;
            case 'treports':
                $sec = 'Treasure reports';
                break;
            case 'frg':
                $sec = 'Finds Recording Guide';
                break;
            case 'secret':
                $sec = 'Britain\'s Secret Treasures';
                break;
            case 'tech':
                $sec = 'Technology';
                break;
            case 'about':
                $sec = 'About us';
                break;
            case 'guides':
                $sec = 'Guides';
                break;
            default:
                $sec = 'Index';
                break;
        }
        return $sec;
    }
}
