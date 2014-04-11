<?php 
 class Pas_Form_Element_RawText extends Zend_Form_Element
    {
        public function render(Zend_View_Interface $view = null)
        {
            return $this->getValue();
        }
    } 
	