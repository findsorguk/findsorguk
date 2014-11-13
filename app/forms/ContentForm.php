<?php

/**
 * Form for submitting and editing content for static pages
 *
 * An example of use:
 *
 * <code>
 * <?php
 * $form = new ContentForm();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014, Daniel Pett
 * @category   Pas
 * @package    Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 2
 * @example /app/modules/admin/controllers/ContentController.php
 * @uses Users
 *
 */
class ContentForm extends Pas_Form
{

    public function __construct(array $options = null)
    {

        $authors = new Users();
        $authorOptions = $authors->getAuthors();

        $sections = new Zend_Config_Ini(APPLICATION_PATH . '/config/sections.ini', 'production');
        $sectionList = $sections->toArray();

        parent::__construct($options);

        $this->setName('addcontent');

        $title = new Zend_Form_Element_Text('title');
        $title->setLabel('Content Title: ')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('size', 60)
            ->addValidator('NotEmpty')
            ->addErrorMessage('You must enter a title');

        $menuTitle = new Zend_Form_Element_Text('menuTitle');
        $menuTitle->setLabel('Menu Title: ')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('size', 60)
            ->addValidator('NotEmpty')
            ->addErrorMessage('You must enter a title');

        $author = new Zend_Form_Element_Select('author');
        $author->setLabel('Set the author of the article: ')
            ->addMultiOptions(array('Choose an author' => $authorOptions))
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addValidator('NotEmpty')
            ->addErrorMessage('You must choose an author');

        $excerpt = new Zend_Form_Element_Textarea('excerpt');
        $excerpt->setLabel('Optional excerpt: ')
            ->setRequired(false)
            ->setAttrib('rows', 5)
            ->setAttrib('cols', 60)
            ->addFilters(array('StripTags', 'StringTrim'));

        $body = new Pas_Form_Element_CKEditor('body');
        $body->setLabel('Main body of text: ')
            ->setRequired(true)
            ->addErrorMessage('You must enter a main body of text')
            ->addFilters(array(
                'StringTrim', 'BasicHtml', 'EmptyParagraph',
                'WordChars'
            ));

        $section = new Zend_Form_Element_Select('section');
        $section->setLabel('Set site section to appear under: ')
            ->addMultiOptions($sectionList)
            ->setRequired(true)
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addErrorMessage('You must choose a section for this to be filed under');

        $parentcontent = new Zend_Form_Element_Select('parent');
        $parentcontent->setLabel('Does this have a parent?: ')
            ->setRequired(false)
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow');

        $metaKeywords = new Zend_Form_Element_Textarea('metaKeywords');
        $metaKeywords->setLabel('Meta keywords: ')
            ->setAttrib('rows', 5)
            ->setAttrib('cols', 60)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setRequired(true);

        $metaDescription = new Zend_Form_Element_Textarea('metaDescription');
        $metaDescription->setLabel('Meta description: ')
            ->setAttrib('rows', 5)
            ->setAttrib('cols', 60)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setRequired(true);

        $publishState = new Zend_Form_Element_Select('publishState');
        $publishState->setLabel('Publishing status: ')
            ->addMultiOptions(array('Please choose publish state' => array('1' => 'Draft',
                '2' => 'Admin to review', '3' => 'Published')))
            ->setValue(1)
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->setRequired(true);

        $slug = new Zend_Form_Element_Text('slug');
        $slug->setLabel('Page slug: ')
            ->setAttrib('size', 50)
            ->addFilter('UrlSlug')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setRequired(true);


        $frontPage = new Zend_Form_Element_Checkbox('frontPage');
        $frontPage->setLabel('Appear on section\'s front page?: ')
            ->addValidators(array('NotEmpty', 'Int'))
            ->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');

        $hash = new Zend_Form_Element_Hash('csrf');
        $hash->setValue($this->_salt)->setTimeout(4800);

        $this->addElements(array(
            $title, $author, $body,
            $section, $publishState, $excerpt,
            $metaKeywords, $metaDescription,
            $slug, $frontPage, $submit,
            $menuTitle, $hash
        ));

        $this->addDisplayGroup(array(
            'title', 'menuTitle', 'author',
            'body', 'section', 'publishState',
            'excerpt', 'metaKeywords', 'metaDescription',
            'slug', 'frontPage'), 'details');

        $this->addDisplayGroup(array('submit'), 'buttons');
        $this->details->setLegend('Add new site content');
        parent::init();
    }
}