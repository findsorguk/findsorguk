// JavaScript Document
jQuery(document).ready(function($) {
$('#county').linkedSelect('/ajax/parishesbycounty/county',
		'#parish',{firstOption: 'Please select a parish',loadingText: 'Loading Please Wait...'});
$('#landusevalue').linkedSelect('/ajax/landusecodes/term/',
		'#landusecode',{firstOption: 'Please select a landuse', loadingText: 'Loading Please Wait...'});
});
