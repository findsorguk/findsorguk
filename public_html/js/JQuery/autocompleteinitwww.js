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
		$('#objecttype')
        .autocomplete('/ajax/objectterm/', acOptions)
        .attr('name', 'objecttype')
        .result(function(e, data) {$('#objecttype').val(data.term);
		});
		$('#old_findID')
        .autocomplete('/ajax/oldfindid/', acOptions)
        .attr('name', 'old_findID')
        .result(function(e, data) {$('#old_findID').val(data.term);
		});
});