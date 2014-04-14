// JavaScript Document
jQuery(document).ready(function($) {
$('#jettonClass').linkedSelect('/database/ajax/getclassestoken',
		'#jettonGroup',{firstOption: 'Please select a group'});
$('#jettonGroup').linkedSelect('/database/ajax/gettypesgroup',
		'#jettonType',{firstOption: 'Please select a type'});
});
