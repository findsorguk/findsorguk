<?php

/**
 * Sends a file for download
 *
 * @category Noginn
 * @copyright Copyright (c) 2009 Tom Graham (http://www.noginn.com)
 * @license http://www.opensource.org/licenses/mit-license.php
 */
class Pas_Controller_Action_Helper_SendFile extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Set cache headers
     *
     * @param array $options
     */
    public function setCacheHeaders($options)
    {
        $response = $this->getResponse();
        
        $cacheControl = array();
        if (isset($options['public']) && $options['public']) {
            $cacheControl[] = 'public';
        }
        if (isset($options['no-cache']) && $options['no-cache']) {
            $cacheControl[] = 'no-cache';
        }
        if (isset($options['no-store']) && $options['no-store']) {
            $cacheControl[] = 'no-store';
        }
        if (isset($options['must-revalidate']) && $options['must-revalidate']) {
            $cacheControl[] = 'must-revalidate';
        }
        if (isset($options['proxy-validation']) && $options['proxy-validation']) {
            $cacheControl[] = 'proxy-validation';
        }
        if (isset($options['max-age'])) {
            $cacheControl[] = 'max-age=' . (int) $options['max-age'];
            $response->setHeader('Expires', gmdate('r', time() + $options['max-age']), true);
        }
        if (isset($options['s-maxage'])) {
            $cacheControl[] = 's-maxage=' . (int) $options['s-maxage'];
        }

        $response->setHeader('Cache-Control', implode(',', $cacheControl), true);
        $response->setHeader('Pragma', 'public', true);
    }

    /**
     * Validate the cache using the If-Modified-Since request header
     *
     * @param int $modified When the file was last modified as a unix timestamp
     * @return bool
     */
    public function notModifiedSince($modified)
    {
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $modified <= strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            // Send a 304 Not Modified header
            $response = $this->getResponse();
            $response->setHttpResponseCode(304);
            $response->sendHeaders();
            return true;
        }

        return false;
    }

    /**
     * Send a file for download
     *
     * @param string $path Path to the file
     * @param string $type The mime-type of the file
     * @param array $options
     * @return bool Whether the headers and file were sent
     */
    public function sendFile($path, $type, $options = array())
    {
        $response = $this->getResponse();
        
       /*  if (!is_readable($path) || !$response->canSendHeaders()) {
            return false;
        } */

        // Set the cache-control
        if (isset($options['cache'])) {
            $this->setCacheHeaders($options['cache']);
        }

        // Get the last modified time
        if (isset($options['modified'])) {
            $modified = (int) $options['modified'];
        } else {
            $modified = filemtime($path);
        }

        // Validate the cache
        if (!isset($options['cache']['no-store']) && $this->notModifiedSince($modified)) {
            return true;
        }

        // Set the file name
        if (isset($options['filename']) && !empty($options['filename'])) {
            $filename = $options['filename'];
        } else {
            $filename = basename($path);
        }

        // Set the content disposition
        if (isset($options['disposition']) && $options['disposition'] == 'inline') {
            $disposition = 'inline';
        } else {
            $disposition = 'attachment';
        }

        $response->setHttpResponseCode(200);

        $response->setHeader('Content-Type', $type, true);
        $response->setHeader('Content-Disposition', $disposition . '; filename="' . $filename . '"', true);

        // Do we want to use the X-Sendfile header or stream the file
        if (isset($options['xsendfile']) && $options['xsendfile']) {
            $response->setHeader('X-Sendfile', $path);
            $response->sendHeaders();
            return true;
        }

        $response->setHeader('Last-Modified', gmdate('r', $modified), true);
        $response->setHeader('Content-Length', filesize($path), true);
        $response->sendHeaders();

        readfile($path);
        return true;
    }

    /**
     * Send file data as a download
     *
     * @param string $path Path to the file
     * @param string $type The mime-type of the file
     * @param string $filename The filename to send the file as, if null then use the base name of the path
     * @param array $options
     * @return bool Whether the headers and file were sent
     */
    public function sendData($data, $type, $filename, $options = array())
    {
        $response = $this->getResponse();
        
        if (!$response->canSendHeaders()) {
            return false;
        }

        // Set the cache-control
        if (isset($options['cache'])) {
            $this->setCacheHeaders($options['cache']);
        }
        
        if (isset($options['modified'])) {
            // Validate the cache
            if (!isset($options['cache']['no-store']) && $this->notModifiedSince($options['modified'])) {
                return true;
            }
            
            $response->setHeader('Last-Modified', gmdate('r', $options['modified']), true);
        }

        // Set the content disposition
        if (isset($options['disposition']) && $options['disposition'] == 'inline') {
            $disposition = 'inline';
        } else {
            $disposition = 'attachment';
        }
        
        $response->setHttpResponseCode(200);
        $response->setHeader('Content-Type', $type, true);
        $response->setHeader('Content-Disposition', $disposition . '; filename="' . $filename . '"', true);
        $response->setHeader('Content-Length', strlen($data), true);
        $response->sendHeaders();

        echo $data;
        return true;
    }

    /**
     * Proxy method for sendFile
     *
     * @param string $path Path to the file
     * @param string $type The mime-type of the file
     * @param array $options
     * @return bool Whether the headers and file were sent
     */
    public function direct($path, $type, $options = array())
    {
        return $this->sendFile($path, $type, $options);
    }
}