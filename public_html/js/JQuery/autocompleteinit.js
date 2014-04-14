// JavaScript Document
var acOptions = {
    minChars: 3,
    max: 10,
    dataType: 'json', // this parameter is currently unused

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
		$('#mack_type')
        .autocomplete('/ajax/macktypes/', acOptions)
        .attr('name', 'mack_type')
        .result(function(e, data) {$('#mack_type').val(data.term);
		});
		$('#allen_type')
        .autocomplete('/ajax/allentypes/', acOptions)
        .attr('name', 'allen_type')
        .result(function(e, data) {$('#allen_type').val(data.term);
		});
		$('#va_type')
        .autocomplete('/ajax/vatypes/', acOptions)
        .attr('name', 'va_type')
        .result(function(e, data) {$('#va_type').val(data.term);
		});
		$('#TID')
        .autocomplete('/ajax/treasureids/', acOptions)
        .attr('name', 'TID')
        .result(function(e, data) {$('#TID').val(data.term);
		});
		$('#otherref')
        .autocomplete('/ajax/otherrefs/', acOptions)
        .attr('name', 'otherref')
        .result(function(e, data) {$('#otherref').val(data.term);
		});
		$('#objecttype')
        .autocomplete('/ajax/objectterm/', acOptions)
        .attr('name', 'objecttype')
        .result(function(e, data) {$('#objecttype').val(data.term);
		});
		$('#publicationtitle')
        .autocomplete('/ajax/publicationtitle/', acOptions)
        .attr('name', 'publicationtitle')
        .result(function(e, data) {	
		$('#publicationtitle').val(data.term); 
		$('#pubID').val(data.id);}
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
		$('#finder2ID').val(data.id);}
		);
		$('#recordby')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'recordby')
        .result(function(e, data) {
		$('#recordby').val(data.term);
		$('#recorderID').val(data.id);
		});
		
		$('#idby')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'idby')
        .result(function(e, data) {
		$('#idby').val(data.term);
		$('#identifierID').val(data.id);
		});
	
		$('#id2by')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'id2by')
        .result(function(e, data) {
		$('#id2by').val(data.term);
		$('#identifierID').val(data.id);
		});
		
		$('#landownername')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'landownername')
        .result(function(e, data) {	
		$('#landownername').val(data.term); 
		$('#landowner').val(data.id);}
		);
		
		$('#monumentName')
        .autocomplete('/ajax/monnames/', acOptions)
        .attr('name', 'monumentName')
        .result(function(e, data) {	
		$('#monumentName').val(data.term); 
        });
});