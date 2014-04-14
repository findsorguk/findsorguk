// JavaScript Document
jQuery(document).ready(function($) {
$('#denomination').linkedSelect('/ajax/romandenomruler/','#ruler',{firstOption: 'Please select a ruler', loadingText: 'Loading Please Wait...'});

$('#ruler').linkedSelect('/ajax/romanmintruler/','#mint',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});
$('#ruler').linkedSelect('/ajax/romanmintruler/','#mint',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});

$('#ruler').linkedSelect('/ajax/reece/','#reeceID',{firstOption: 'Please select Reece Period', loadingText: 'Loading Please Wait...'});

$('#ruler').linkedSelect('/ajax/moneyers/','#moneyer',{firstOption: 'Please select Republican Moneyer', loadingText: 'Loading Please Wait...'});


$('#ruler').linkedSelect('/ajax/revtypes/','#revtypeID',{firstOption: 'Please select reverse type', loadingText: 'Loading Please Wait...'});

$('#county').linkedSelect('/ajax/parishesbycounty/county',
		'#parish',{firstOption: 'Please select a parish',loadingText: 'Loading Please Wait...'});

$('#landusevalue').linkedSelect('/ajax/landusecodes/term/',
		'#landusecode',{firstOption: 'Please select a landuse', loadingText: 'Loading Please Wait...'});

});