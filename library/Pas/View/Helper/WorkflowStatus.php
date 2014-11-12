<?php

/**
 * A view helper for displaying workflow as a textual representation
 *
 * An example of use:
 *
 * <code>
 * <?php
 * echo $this->workflowStatus()->setWorkflow(1);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @example /app/views/scripts/email/informFinderWorkflow.phtml
 */
class Pas_View_Helper_WorkflowStatus
{

    /** Default workflow status
     * @access protected
     * @var int
     */
    protected $_secwfstage = 1;

    /** Get the workflow stage
     * @access public
     * @return type
     */
    public function getSecwfstage()
    {
        return $this->_secwfstage;
    }

    /** Set the Workflow status
     * @access public
     * @param  int $secwfstage
     * @return \Pas_View_Helper_Workflow
     */
    public function setWorkflow($secwfstage)
    {
        $this->_secwfstage = $secwfstage;
        return $this;
    }

    /** The workflow class
     * @access public
     * @return \Pas_View_Helper_Workflow
     */
    public function workflow()
    {
        return $this;
    }

    /** The magic to string method
     * @access public
     * @return type
     */
    public function __toString()
    {
        return $this->_buildHtml();
    }

    /** Render the html string
     * @access public
     * @return string
     */
    public function _buildHtml()
    {
        switch ($this->getSecwfstage()) {
            case 1:
                $wf = 'Quarantine';
                break;
            case 2:
                $wf = 'On review';
                break;
            case 4:
                $wf = 'Awaiting validation';
                break;
            case 3:
                $wf = 'Published';
                break;
            default:
                $wf = 'No workflow status set';
                break;
        }
        return $wf;
    }

    /** The function to call
     * @access public
     * @return \Pas_View_Helper_WorkflowStatus
     */
    public function workflowStatus()
    {
        return $this;
    }
}