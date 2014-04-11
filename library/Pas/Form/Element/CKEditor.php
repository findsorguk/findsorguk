<?php
class Pas_Form_Element_CKEditor extends ZendX_JQuery_Form_Element_UiWidget
{
    /**
     * Use formCKeditor view helper by default
     * @var string
     */
    public $helper = 'formCKEditor';

    /**
     * Default ckeditor options
     *
     * @var array
     */
    public $jQueryParams = array(
//        'toolbar' => 'Basic'
    );
}