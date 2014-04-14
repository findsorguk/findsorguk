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


		$('#reporter')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'reporter')
        .result(function(e, data) {	
		$('#reporter').val(data.term); 
		$('#reporterID').val(data.id);}
		);
			
		$('#sam')
        .autocomplete('/ajax/sams/', acOptions)
        .attr('name', 'sam')
        .result(function(e, data) {	
		$('#sam').val(data.term);
		$('#samID').val(data.id);}
        );
});