/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	 config.extraPlugins = 'wordcount, notification, htmlwriter, colorbutton, colordialog';

	 config.removePlugins = 'iframe, forms, flash, div';

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	config.entities_latin = true;

	config.scayt_sLang = 'en_GB';

	//Enable SpellCheck
	config.scayt_autoStartup = true;

	config.colorButton_backStyle = {
	    element: 'span',
	    styles: { 'background-color': '#(color)' }
	};

	config.colorButton_foreStyle = {
	    element: 'span',
	    styles: { color: '#(color)' }
	};

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

        config.wordcount = {

            // Whether or not you want to show the Paragraphs Count
            showParagraphs: false,

            // Whether or not you want to show the Word Count
            showWordCount: false,

            // Whether or not you want to show the Char Count
            showCharCount: true,

            // Whether or not you want to count Spaces as Chars
            countSpacesAsChars: true,

            // Whether or not to include Html chars in the Char Count
            countHTML: true,

            // Whether or not to include Line Breaks in the Char Count
            countLineBreaks: true,

            // Maximum allowed Word Count, -1 is default for unlimited
            maxWordCount: -1,

            // Maximum allowed Char Count, -1 is default for unlimited
            maxCharCount: 65000,

            // Maximum allowed Paragraphs Count, -1 is default for unlimited
            maxParagraphs: -1,

            // How long to show the 'paste' warning, 0 is default for not auto-closing the notification
            pasteWarningDuration: 0,
     };
};
