<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 */

class Pas_View_Helper_ParliamentCareer extends Zend_View_Helper_Abstract{

    /** Loop through a person's parliamentary career
     *
     * @param array $data
     * @return string
     */
    public function parliamentCareer($data){
        if(is_array($data)){
        $html .= '<div id="career">';
        foreach($data as $d){
        $html .= $this->view->partial('partials/news/mp.phtml',
                $d);
        }
        $html .= '</div>';
        return $html;
        } else {
            throw new Pas_Exception_BadJuJu('Career data is not an array');
        }
    }

}
