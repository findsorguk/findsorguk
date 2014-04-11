<?php

class Pas_View_Helper_SiteWideSearch extends Zend_View_Helper_Abstract {

    public function siteWideSearch(){
        $form = new SiteWideForm();
        return $form;
    }
}