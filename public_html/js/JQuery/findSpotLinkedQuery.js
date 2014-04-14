// JavaScript Document
jQuery(document).ready(function($) {
$('#countyID').linkedSelect('/ajax/osregionsbycounty',
		'#regionID',{firstOption: 'Please select a region'});
$('#countyID').linkedSelect('/ajax/osdistrictsbycounty',
		'#districtID',{firstOption: 'Please select a district'});
$('#districtID').linkedSelect('/ajax/osparishesbydistrict',
                '#parishID',{firstOption: 'Please select a parish'});
$('#landusevalue').linkedSelect('/ajax/landusecodes/',
		'#landusecode',{firstOption: 'Please select a landuse'});
});
