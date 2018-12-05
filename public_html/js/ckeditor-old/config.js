/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        config.allowedContent = true;
	
	config.toolbar = [
	                  { name: 'document', items: [ 'Source' ] },
	                  { name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	                  '/',
	                  { name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
                          { name: 'insert', items: [ 'Table'] }
	              ];
};
