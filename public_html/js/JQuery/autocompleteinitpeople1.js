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
  		 $('#fullname')
        .autocomplete('/ajax/peoplesearch/', acOptions)
        .attr('name', 'fullname')
        .result(function(e, data) {	
		$('#fullname').val(data.term); 
		}
		);
		
		$('#username')
        .autocomplete('/ajax/username/', acOptions)
        .attr('name', 'username')
        .result(function(e, data) {	
		$('#username').val(data.term); 
		}
		);
		
		$('#organisation')
        .autocomplete('/ajax/organisation/', acOptions)
        .attr('name', 'organisation')
        .result(function(e, data) {	
		$('#organisation').val(data.term); 
		$('#organisationID').val(data.id);}
		);
		
		$('#contact')
        .autocomplete('/ajax/people/', acOptions)
        .attr('name', 'contact')
        .result(function(e, data) {	
		$('#contact').val(data.term); 
		$('#contactpersonID').val(data.id);}
		);
		
		$('#person')
        .autocomplete('/ajax/peoplesearch/', acOptions)
        .attr('name', 'person')
        .result(function(e, data) {	
		$('#person').val(data.term); 
		$('#peopleID').val(data.id);}
		);
		
});