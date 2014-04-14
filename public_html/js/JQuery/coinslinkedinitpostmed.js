// JavaScript Document
jQuery(document).ready(function($) {
 
$('#category').linkedSelect('/ajax/postmedcatruler/','#ruler_id',{firstOption: 'Please select a ruler', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('/ajax/medmintruler/','#mint',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('/ajax/earlymedtyperuler/','#type',{firstOption: 'Please select a type', loadingText: 'Loading Please Wait...'});


$('#ruler_id').linkedSelect('/ajax/rulerdenomearlymed/','#denomination',{firstOption: 'Please select a denomination', loadingText: 'Loading Please Wait...'});


});