// JavaScript Document
jQuery(document).ready(function($) {

$('#denomination').linkedSelect('http://localhost/redev/beowulf/ajax/romandenomruler/','#ruler',{firstOption: 'Please select a ruler'});

$('#ruler').linkedSelect('http://localhost/redev/beowulf/ajax/romanmintruler/','#mint',{firstOption: 'Please select a mint', loadingText: 'Loading Please Wait...'});

$('#ruler').linkedSelect('http://localhost/redev/beowulf/ajax/reece/','#reece',{firstOption: 'Please select Reece Period', loadingText: 'Loading Please Wait...'});

$('#ruler').linkedSelect('http://localhost/redev/beowulf/ajax/moneyers/','#moneyer',{firstOption: 'Please select Republican Moneyer', loadingText: 'Loading Please Wait...'});


$('#ruler').linkedSelect('http://localhost/redev/beowulf/ajax/revtypes/','#revtype',{firstOption: 'Please select reverse type', loadingText: 'Loading Please Wait...'});

});