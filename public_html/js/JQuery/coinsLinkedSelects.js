// JavaScript Document
jQuery(document).ready(function($) {



$('#denomname').linkedSelect('http://localhost/redev/ajax/romandenomruler/','#ruler_id',{firstOption: 'Please select a ruler', loadingText: 'Loading Please Wait...'});


$('#ruler_id').linkedSelect('http://localhost/redev/ajax/romanmintruler/','#mint',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('http://localhost/redev/ajax/reece/','#reece',{firstOption: 'Please select Reece Period', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('http://localhost/redev/ajax/moneyers/','#moneyer',{firstOption: 'Please select Republican Moneyer', loadingText: 'Loading Please Wait...'});

$('#ruler_id').linkedSelect('http://localhost/redev/ajax/revtypes/','#reverse',{firstOption: 'Please select reverse type', loadingText: 'Loading Please Wait...'});

});