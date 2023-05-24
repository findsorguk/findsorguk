<?php

/** An action helper for sending mail from a controller.
 * An example of code use:
 * <code>
 * <?php
 *  $this->_helper->mailer($assignData, 'publicFindToFlo', $to, $cc, $from);
 * ?>
 * </code>
 *
 * @author        Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category      Pas
 * @package       Controller_Action
 * @subpackage    Helper
 * @uses          Zend_Controller_Action_Helper_Abstract
 * @uses          Zend_Mail
 * @uses          Zend_View
 * @uses          Pas_Filter_EmailTextOnly
 * @license       http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example       /app/modules/database/controllers/ArtefactsController.php
 */
class Pas_Controller_Action_Helper_Mailer extends Zend_Controller_Action_Helper_Abstract
{

    /** The view object
     *
     * @access protected
     * @var \Zend_View
     */
    protected $_view;

    /** The templates
     *
     * @access protected
     * @var array
     */
    protected $_templates;

    /** Mail object
     *
     * @access protected
     * @var \Zend_Mail
     */
    protected $_mail;

    /** The markdown flag
     *
     * @access protected
     * @var \Pas_Filter_EmailTextOnly
     */
    protected $_markdown;

    /** Array of types
     *
     * @access protected
     * @var array
     */
    protected $_types;

    /** Transactions email address and name
     *
     * @access protected
     */
    protected $_transactionsEmail;
    protected $_transactionsEmailName;

    /** Initialise the objects and class
     */
    public function init()
    {
        $this->_view = new Zend_View();
        $this->_mail = new Zend_Mail('utf-8');
        $this->_view->mail = $this->_mail;
        $this->_templates = APPLICATION_PATH . '/views/scripts/email/';
        $this->_markdown = new Pas_Filter_EmailTextOnly();
        $this->_types = $this->getTypes();
        $this->_transactionEmail = end_Registry::get('config')->transaction->email;
        $this->_transactionEmailName = end_Registry::get('config')->transaction->name;
    }

    /** Get the types of template available
     *
     * @return array
     */
    private function getTypes()
    {
        $dir = new DirectoryIterator($this->_templates);
        $files = array();
        foreach ($dir as $dirEntry) {
            if ($dirEntry->isFile() && !$dirEntry->isDot()) {
                $filename = $dirEntry->getFilename();
                //                $pathname = $dirEntry->getPathname();
                if (preg_match('/^(.+)\.phtml$/', $filename, $match)) {
                    $files[] = $match[1];
                }
            }
        }
        return $files;
    }

    /** direct method for action controller
     *
     * @access public
     * @param array  $assignData
     * @param string $type
     * @param array  $to
     * @param array  $cc
     * @param array  $from
     * @param array  $bcc
     * @param array  $attachments
     */
    public function direct(
        array $assignData = null,
        $type,
        array $to = null,
        array $cc = null,
        array $from = null,
        array $bcc = null,
        array $attachments = null
    ) {
        $script = $this->_getTemplate($type);
        $message = $this->_view->setScriptPath($this->_templates);
        $this->_view->addHelperPath('Pas/View/Helper/', 'Pas_View_Helper');
        $message->assign($assignData);
        $html = $message->render($script);
        $text = $this->_stripper($html);
        $this->_mail->addHeader('X-MailGenerator', 'Portable Antiquities Scheme');
        $this->_mail->setBodyHtml($html);
        $this->_mail->setBodyText($text);
        $this->_setUpSending($to, $cc, $from, $bcc);
        if (!is_null($attachments)) {
            $this->_addAttachments($attachments);
        }
        $this->_sendIt();
    }

    /** Add attachments
     *
     * @access protected
     * @param array $attachments
     * @throws Exception
     * @todo   test function
     */
    protected function _addAttachments(array $attachments)
    {
        if (is_array($attachments)) {
            foreach ($attachments as $attach) {
                $filter = new Zend_Filter_BaseName();
                $file = file_get_contents($attach);
                $addition = $this->_mail->createAttachment($file);
                $addition->disposition = Zend_Mime::DISPOSITION_INLINE;
                $addition->encoding = Zend_Mime::ENCODING_BASE64;
                $addition->filename = $filter->filter($attach);
            }
        } else {
            throw new Exception('The attachment list is not an array.');
        }
    }

    /** Set up sending to addresses
     *
     * @access protected
     * @param array $to
     * @param array $cc
     * @param array $from
     * @param array $bcc
     */
    protected function _setUpSending($to, $cc, $from, $bcc)
    {
        if (is_array($to)) {
            foreach ($to as $addTo) {
                $this->_mail->addTo($addTo['email'], $addTo['name']);
            }
        } else {
            $this->_mail->addTo($this->_transactionEmail, $this->_transactionEmailName);
        }
        if (is_array($cc)) {
            foreach ($cc as $addCc) {
                $this->_mail->addCc($addCc['email'], $addCc['name']);
            }
        }
        if (is_array($from)) {
            foreach ($from as $addFrom) {
                $this->_mail->setFrom($addFrom['email'], $addFrom['name']);
            }
        } else {
            $this->_mail->setFrom($this->_transactionEmail, $this->_transactionEmailName);
        }
        if (is_array($bcc)) {
            foreach ($bcc as $addBcc) {
                $this->_mail->addBcc($addBcc['email'], $addBcc['name']);
            }
        }
    }

    /** Strip out html using html purifier
     *
     * @access protected
     * @param string $string
     * @return string
     */
    protected function _stripper($string)
    {
        $clean = $this->_markdown->filter($string);
        return $clean;
    }

    /** Send the email using Zend Mail
     *
     * @access protected
     * @return \Pas_Controller_Action_Helper_Mailer
     */
    protected function _sendIt()
    {
        return $this->_mail->send();
    }

    /** Determine the template to use
     *
     * @access protected
     * @param string $type
     * @return string
     * @throws Exception
     */
    protected function _getTemplate($type)
    {
        if (!is_null($type) && in_array($type, $this->_types)) {
            $script = $type . '.phtml';
        } else {
            throw new Exception('That template does not exist', 500);
        }
        return $script;
    }
}