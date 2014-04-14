// JavaScript Document
jQuery(document).ready(function($) {
 
$('#category').linkedSelect('/ajax/earlymedcatruler/','#ruler',{firstOption: 'Please select a ruler', loadingText: 'Loading Please Wait...'});

$('#ruler').linkedSelect('/ajax/earlymedmintruler/','#mint',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});

$('#ruler').linkedSelect('/ajax/earlymedtyperuler/','#type',{firstOption: 'Please select a type', loadingText: 'Loading Please Wait...'});


$('#ruler').linkedSelect('/ajax/rulerdenomearlymed/','#denomination',{firstOption: 'Please select a denomination', loadingText: 'Loading Please Wait...'});


});