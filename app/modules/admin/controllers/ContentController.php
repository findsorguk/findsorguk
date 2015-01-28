<?php

/** Controller for adding static contents to the Scheme website
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses Content
 * @uses ContentSearchForm
 * @uses Pas_Solr_Handler
 * @uses ContentForm
 * @uses Pas_Exception_Param
 * @uses Pas_ArrayFunctions
 *
 */
class Admin_ContentController extends Pas_Controller_Action_Admin
{

    /** The content model
     * @access protected
     * @var \Content
     */
    protected $_content;

    /** The cleaning class
     * @access protected
     * @var \Pas_ArrayFunctions
     */
    protected $_cleaner;

    /** The array class
     * @access public
     * @return \Pas_ArrayFunctions
     */
    public function getCleaner()
    {
        $this->_cleaner = new Pas_ArrayFunctions();
        return $this->_cleaner;
    }

    /** Initialise the ACL and contexts
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);
        $this->_content = new Content();

    }

    /** Display index page
     * Display all content in the system Solr indexed
     * @access public
     * @return void
     */
    public function indexAction()
    {
        $form = new ContentSearchForm();
        $form->submit->setLabel('Search content');
        $this->view->form = $form;
        $params = $this->getCleaner()->array_cleanup($this->getAllParams());
        $search = new Pas_Solr_Handler();
        $search->setCore('content');

        if ($this->getRequest()->isPost() && !is_null($this->getParam('submit'))) {
            if ($form->isValid($this->_request->getPost())) {
                $params = $this->getCleaner()->array_cleanup($form->getValues());
                $this->_helper->Redirector->gotoSimple('index', 'content', 'admin', $params);
            } else {
                $form->populate($form->getValues());
                $params = $form->getValues();
            }
        } else {
            $form->populate($this->getAllParams());
        }
        if (!isset($params['q']) || $params['q'] == '') {
            $params['q'] = '*';
        }
        $params['type'] = 'sitecontent';
        $params['page'] = $this->getParam('page');
        $search->setParams($params);
        $search->execute();
        $this->view->paginator = $search->createPagination();
        $this->view->contents = $search->processResults();
    }

    /** Add new content
     * @access public
     * @return void
     */
    public function addAction()
    {
        $form = new ContentForm();
        $form->submit->setLabel('Add new content to system');
        $form->author->setValue($this->getIdentityForForms());
        $this->view->form = $form;
        if ($this->getRequest()->isPost()
            && $form->isValid($this->_request->getPost())
        ) {
            if ($form->isValid($form->getValues())) {
                $insertData = $form->getValues();
                $content = new Content();
                $insert = $content->add($insertData);
                $this->_helper->solrUpdater->update('content', $insert, 'content');
                $this->getFlash()->addMessage('Static content added');
                $this->redirect('/admin/content');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a content article
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editAction()
    {
        if ($this->getParam('id', false)) {
            $form = new ContentForm();
            $form->submit->setLabel('Submit changes');
            $form->author->setValue($this->getIdentityForForms());
            $this->view->form = $form;
            if ($this->getRequest()->isPost()
                && $form->isValid($this->_request->getPost())
            ) {
                if ($form->isValid($form->getValues())) {
                    $updateData = $form->getValues();
                    $where = array();
                    $where[] = $this->_content->getAdapter()
                        ->quoteInto('id = ?', $this->getParam('id'));
                    $oldData = $this->_content->fetchRow($this->_content->select()->where('id= ?', (int)$this->getParam('id')))->toArray();
                    $this->_helper->audit($updateData, $oldData, 'ContentAudit', $this->getParam('id'), $this->getParam('id'));
                    $this->_content->update($updateData, $where);
                    $this->_helper->solrUpdater->update('content', $this->getParam('id'), 'content');
                    $this->getFlash()->addMessage('You updated successfully. It is now available for use.');
                    $this->getCache()->clean(Zend_Cache::CLEANING_MODE_ALL);
                    $this->redirect('admin/content/');
                } else {
                    $form->populate($this->_request->getPost());
                }
            } else {
                // find id is expected in $params['id']
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $content = $this->_content->fetchRow('id=' . (int)$id)->toArray();
                    if ($content) {
                        $form->populate($content);
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete article
     * @access public
     * @return void
     */
    public function deleteAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $where = 'id = ' . $id;
                $this->getContents()->delete($where);
                $this->getFlash()->addMessage('Record deleted!');
                $this->_helper->solrUpdater->deleteById('content', $id);
            }
            $this->redirect('/admin/content/');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $this->view->content = $this->getContents()->fetchRow('id=' . $id);
            }
        }
    }
}