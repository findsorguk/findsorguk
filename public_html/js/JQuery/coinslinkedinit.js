// JavaScript Document
jQuery(document).ready(function($) {
$('#denomination').linkedSelect('/ajax/romandenomruler/','#ruler_id',{firstOption: 'Please select a ruler', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('/ajax/romanmintruler/','#mint',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});
$('#ruler_id').linkedSelect('/ajax/romanmintruler/','#mint_id',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('/ajax/reece/','#reeceID',{firstOption: 'Please select Reece Period', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('/ajax/moneyers/','#moneyer',{firstOption: 'Please select Republican Moneyer', loadingText: 'Loading Please Wait...'});


$('#ruler_id').linkedSelect('/ajax/revtypes/','#revtypeID',{firstOption: 'Please select reverse type', loadingText: 'Loading Please Wait...'});

});