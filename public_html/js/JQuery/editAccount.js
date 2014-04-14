// JavaScript Document
var acOptions = {
    minChars: 3,
    max: 15,
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
		$('#person')
        .autocomplete('/ajax/peoplesearch/', acOptions)
        .attr('name', 'person')
        .result(function(e, data) {	
		$('#person').val(data.term); 
		$('#peopleID').val(data.id);}
		);
		
});