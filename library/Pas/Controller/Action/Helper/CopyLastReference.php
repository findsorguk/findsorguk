<?php

class Pas_Controller_Action_Helper_CopyLastReference extends Zend_Controller_Action_Helper_Abstract
{
    public function direct($form, $reference)
    {
        return $this->copyReferenceToForm($reference, $form);
    }

    // Copy reference to the form
    private function copyReferenceToForm($reference, $form)
    {
	if ($reference)
	{
	    $this->checkAndSetValueForFormField($reference, $form, 'authors');
	    $this->checkAndSetValueForPubTitle($reference, $form);
	    $this->checkAndSetValueForFormField($reference, $form, 'pages_plates');
	    $this->checkAndSetValueForFormField($reference, $form, 'reference');
	}
    }

    // Check if the value exists for the key, then set it to the form field(s)
    private function checkAndSetValueForFormField($reference, $form, $key)
    {
        if ($this->checkValidData($key, $reference, $form))
        {
            $this->setFormValue($key, $reference, $form);
        }
    }

    // Populate the Publication title of the form
    private function checkAndSetValueForPubTitle($reference, $form)
    {
        if ($this->checkValidData('authors', $reference, $form)
            && $this->checkValidData('pubID', $reference, $form)
            && $this->checkValidData('title', $reference, $form))
        {
            $this->setPubTitle($reference, $form);
        }
    }

    // Check the form input column exist in the retrieved reference
    private function checkValidData($key, $reference, $form)
    {
	return (array_key_exists($key, $reference) && !is_null($reference[$key]));
    }

    // Set the form input column value to the retrieved reference
    private function setFormValue($key, $reference, $form)
    {
        $form->$key->setValue($reference[$key]);
    }

    // Set the Publication title value to the retrieved reference
    private function setPubTitle($reference, $form)
    {
        $pubs = new Publications();
        $titles = $pubs->getTitlesPairs($reference['authors']);
        $form->pubID->addMultiOptions($titles);

	$this->setFormValue('pubID', $reference, $form);
    }
}
