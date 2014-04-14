// JavaScript Document
var acOptions = {
    minChars: 3,
    max: 15,
    dataType: 'json', 

    parse: function(data) {
        var parsed = [];
 
        for (var i = 0; i < data.length; i++) {
            parsed[parsed.length] = {
                data: data[i],
                value: data[i].id,
                result: data[i].term
            };
        }
 
        return parsed;
    },
    formatItem: function(item) {
        return item.term;
    }
};

jQuery(document).ready(function($) {


		$('#old_findID')
        .autocomplete('/ajax/oldfindid/', acOptions)
        .attr('name', 'old_findID')
        .result(function(e, data) {$('#old_findID').val(data.term);
		});
		
		$('#objecttype')
        .autocomplete('/ajax/objectterm/', acOptions)
        .attr('name', 'objecttype')
        .result(function(e, data) {$('#objecttype').val(data.term);
		});
	
		$('#organisername')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'organisername')
        .result(function(e, data) {	
		$('#organisername').val(data.term); 
		$('#organiser').val(data.id);}
		);
		
		$('#investigatorname')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'investigatorname')
        .result(function(e, data) {	
		$('#investigatorname').val(data.term); 
		$('#investigator').val(data.id);}
		);
		
		$('#finder')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'finder')
        .result(function(e, data) {	
		$('#finder').val(data.term); 
		$('#finderID').val(data.id);}
		);
		
		$('#secondfinder')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'finder2ID')
        .result(function(e, data) {
		$('#secondfinder').val(data.term); 
		$('#finder2ID').val(data.id);
		});
		
		$('#recordername')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'recordername')
        .result(function(e, data) {
		$('#recordername').val(data.term);
		$('#recorderID').val(data.id);
		});
		
		$('#idBy')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'idBy')
        .result(function(e, data) {
		$('#idBy').val(data.term);
		$('#identifier1ID').val(data.id);
		});
	
		$('#id2by')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'id2by')
        .result(function(e, data) {
		$('#id2by').val(data.term);
		$('#identifier2ID').val(data.id);
		});
});