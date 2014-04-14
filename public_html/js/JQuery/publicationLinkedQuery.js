// JavaScript Document
jQuery(document).ready(function($) {
$('#authors').linkedSelect('/ajax/publications',
		'#pubID',{firstOption: 'Please select a publication'});
});
