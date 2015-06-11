<?php
/** A form for uploading images to the database using the blueimp uploader.
 * An example of code:
 *
 * <code>
 * <?php
 * $form = new UploadForm();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 */

class UploadForm extends Zend_Form {

    public function init()
    {
        $this->setAttrib('id', 'uploader');

        $this->addElement(
            'file',
            'fileupload',
            array(
                'class' => 'btn btn-success fileinput-button',
                'attributes' => 'multiple',
                'title' => 'Search for files'

            )
        );
        $this->addElement(
            'Button',
            'start',
            array(
                'label' => 'Start Upload',
                'class' => 'btn btn-primary start',
                'escape' => false
            )
        );

        $this->addElement(
            'Button',
            'cancel',
            array(
                'label' => 'Cancel Upload',
                'class' => 'btn btn-warning cancel',
                'escape' => false
            )
        );
        $this->addElement(
            'Button',
            'delete',
            array(
                'label' => 'Delete',
                'class' => 'btn btn-danger delete',
                'escape' => false
            )
        );
        $this->fileupload->setDecorators(
            array(
                'File',
                'Errors',
                array(array('data' => 'HtmlTag'), array('tag' => 'span')),
                array('Label', array('tag' => 'span')),
                array(array('row' => 'HtmlTag'), array('tag' => 'span'))
            )
        );
        $this->start->setDecorators(array('ViewHelper'));
        $this->cancel->setDecorators(array('ViewHelper'));
        $this->delete->setDecorators(array('ViewHelper'));

        $this->setAction('/database/ajax/upload');

        parent::init();
    }

} 