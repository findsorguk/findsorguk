// JavaScript Document
jQuery(document).ready(function($) {
$('#denomination').linkedSelect('/ajax/iageography/','#geographyID',{firstOption: 'Please select a geographic region'});
$('#geographyID').linkedSelect('/ajax/iarulerregion/','#ruler_id',{firstOption: 'Please select a ruler'});
$('#geographyID').linkedSelect('/ajax/iarulerregion/','#ruler2_id',{firstOption: 'Please select a ruler (not compulsory)'});
$('#geographyID').linkedSelect('/ajax/iatriberegion/','#tribe',{firstOption: 'Please select a tribe'});

});