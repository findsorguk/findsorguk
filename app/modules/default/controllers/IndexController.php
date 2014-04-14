<?php
class IndexController extends Zend_Controller_Action
{
	public function init() {
	$this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
	$this->_helper->acl->allow(null);
	}



	public function indexAction() {

	$content = new Content();
	$this->view->contents = $content->getFrontContent('index');
    $form = new CombinedForm();
 	$form->setAttrib('class', 'form-inline');
        $this->view->form = $form;
        $form->removeElement('thumbnail');
//        $form->removeElement('submit');
        $form->q->removeDecorator('label');
        $form->q->setAttrib('class','input-large');
        if($this->getRequest()->isPost() && $form->isValid($_POST)){
	if ($form->isValid($form->getValues())) {
	$params = array_filter($form->getValues());
        unset($params['csrf']);
	$this->_flashMessenger->addMessage('Your search is complete');
	$this->_helper->Redirector->gotoSimple('database','results','search',$params);
	} else {
	$form->populate($form->getValues());
	}
	}

	}

}