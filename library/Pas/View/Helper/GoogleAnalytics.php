<?php

/**
 * Google analytics view helper
 *
 * @original http://www.zfsnippets.com/snippets/view/id/30
 */
class Pas_View_Helper_GoogleAnalytics
	extends Zend_View_Helper_Placeholder_Container_Standalone
{
    /**
     * @var string registry key
     */
    protected $_regKey = 'Pas_View_Helper_GoogleAnalytics';

    /**
     * Available Trackers options
     */
    static protected $_availableOptions = array
    (
        // Standard Options
        'trackPageview',
        'setVar',

        // ECommerce Options
        'addItem',
        'addTrans',
        'trackTrans',

        // Tracking Options
        'setClientInfo',
        'setAllowHash',
        'setDetectFlash',
        'setDetectTitle',
        'setSessionTimeOut',
        'setCookieTimeOut',
        'setDomainName',
        'setAllowLinker',
        'setAllowAnchor',

        // Campaign Options
        'setCampNameKey',
        'setCampMediumKey',
        'setCampSourceKey',
        'setCampTermKey',
        'setCampContentKey',
        'setCampIdKey',
        'setCampNoKey',

        // Other
        'addOrganic',
        'addIgnoredOrganic',
        'addIgnoredRef',
        'setSampleRate',
    );

    /**
     *
     * @param string $trackerId the google analytics tracker id
     * @param array $options
     * @return App_View_Helper_GoogleAnalytics
     */
    public function GoogleAnalytics($trackerId = null, array $options = array())
    {
        if (!is_null($trackerId)) {

            $this->addTracker($trackerId, $options);
        }

        return $this;
    }

    /**
     * Alias to _addTrackerOption
     *
     * @param string $optionsName
     * @param array $optionsArgs
     * @return App_View_Helper_GoogleAnalytics
     */
    public function __call($optionsName, $optionsArgs)
    {
        if (in_array($optionsName, self::$_availableOptions) === false) {
            throw new Zend_View_Exception('Unknown "' . $optionFunc . '" GoogleAnalytics options');
        }

        if (empty($optionsArgs)) {
            throw new Zend_View_Exception('Missing TrackerId has first Argument on "$this->GoogleAnalytics->' . $optionFunc . '()" function call');
        }

        $trackerId = array_shift($optionsArgs);

        $this->_addTrackerOption($trackerId, $optionsName, $optionsArgs);

        return $this;
    }

    /**
     * Add tracker
     *
     * @param string $id
     * @param array $options
     * @return App_View_Helper_GoogleAnalytics
     */
    public function addTracker($id, array $options = array())
    {
        if (!empty($options)) {
            $this->addTrackerOptions($id, $options);
        }

        $this->trackPageview($id);

        return $this;
    }

    /**
     * Set options
     *
     * @param array $trackers
     * @return App_View_Helper_GoogleAnalytics
     */
    public function setOptions(array $trackers)
    {
        foreach ($trackers as $tracker)
        {
            if (!$tracker['enabled'])
                continue;

            $this->addTracker($tracker['id'], isset($tracker['options']) ? $tracker['options'] : array());
        }

        return $this;
    }

    /**
     * Add options from array
     *
     * @param string $trackerId the google analytics tracker id
     * @param array $options of array option with first value has option name
     * @return App_View_Helper_GoogleAnalytics
     */
    public function addTrackerOptions($trackerId, array $options)
    {
        foreach ($options as $optionsArgs) {

            $optionsName = array_shift($optionsArgs);

            $this->_addTrackerOption($trackerId, $optionsName, $optionsArgs);
        }

        return $this;
    }

    /**
     * Add a tracker option
     *
     * @param string $trackerId the google analytics tracker id
     * @param string $optionsName option name
     * @param array $optionsArgs option arguments
     * @return App_View_Helper_GoogleAnalytics
     */
    protected function _addTrackerOption($trackerId, $optionsName, array $optionsArgs = array())
    {
        $storage = $this->getContainer();

        array_unshift($optionsArgs, $optionsName);

        $options = isset($storage[$trackerId]) ? $storage[$trackerId] : array();

        $options[] = $optionsArgs;

        $storage[$trackerId] = $options;

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
     * Rendering Google Anaytics Tracker script
     *
     * @return string
     */
    public function toString()
    {
        // no code if there is no trackers
        if (count($this->getContainer()) == 0)
            return '';

        $xhtml = array();
        $xhtml[] = '<script type="text/javascript">';
        $xhtml[] = 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");';
        $xhtml[] = 'document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));';
        $xhtml[] = '</script>';
	$xhtml[] = '<script type="text/javascript">';
        $xhtml[] = 'try {';

        $i = 0;
        foreach ($this->getContainer() as $trackerId => $options) {

            // build tracker name
            $trackerInstance = 'pageTracker' . ($i > 0 ? $i : null);

            // init tracker
            $xhtml[] = 'var ' . $trackerInstance . ' = _gat._getTracker("' . $trackerId . '");';

            // add options
            foreach ($options as $optionsData) {

                // build tracker func call
                $optionName = '_' . array_shift($optionsData);

                // escape options arg
                $optionArgs = array();
                foreach ($optionsData as $arg) {
                    $optionArgs[] = (is_numeric($arg) || $arg == 'true' || $arg == 'false') ? $arg : '"' . addslashes($arg) . '"';
                }

                // add options
                $xhtml[] = $trackerInstance . '.' . $optionName . '(' . implode(',', $optionArgs) . ');';
            }

            $i++;
        }

        $xhtml[] = '} catch(err) {}';
        $xhtml[] = '</script>';

        return implode("\n", $xhtml);
    }
}
