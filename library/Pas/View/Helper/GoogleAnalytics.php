<?php

/**
 * Google analytics view helper
 *
 * @category Pas
 * @package View
 * @subpackage Helper
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @original http://www.zfsnippets.com/snippets/view/id/30
 */
class Pas_View_Helper_GoogleAnalytics extends Zend_View_Helper_Placeholder_Container_Standalone
{
    /** The key
     * @var string registry key
     */
    protected $_regKey = 'Pas_View_Helper_GoogleAnalytics';

    protected $_trackerID = NULL;

    /**
     * @return null
     */
    public function getTrackerID()
    {
        return $this->_trackerID;
    }

    /**
     * @param null $trackerID
     */
    public function setTrackerID($trackerID)
    {
        $this->_trackerID = $trackerID;
    }



    /**
     *
     * @param  string $trackerId the google analytics tracker id
     * @param  array $options
     * @return App_View_Helper_GoogleAnalytics
     */
    public function GoogleAnalytics($trackerId = null)
    {
        if (!is_null($trackerId)) {

            $this->setTrackerID($trackerId);
        }

        return $this;
    }



    /**
     * Add tracker
     *
     * @param  string $id
     * @param  array $options
     * @return App_View_Helper_GoogleAnalytics
     */
    public function addTracker()
    {

        return $this;
    }





    /**
     * Cast to string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Rendering Google Analytics Tracker script
     *
     * @return string
     */
    public function toString()
    {

        $xhtml = '';
        $xhtml .= "<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){";
        $xhtml .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),";
        $xhtml .= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)";
        $xhtml .= "})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');\n";

	$xhtml .= "ga('create'," . "'" . $this->getTrackerID() ."', {
			 'cookieDomain': 'finds.org.uk',
			 'cookieFlags': 'SameSite=None; Secure',	 
		});\n";
        $xhtml .= "ga('send', 'pageview');\n";
        $xhtml .= "</script>";

        return $xhtml;
    }
}
