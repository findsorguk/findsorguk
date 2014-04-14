$(document).ready(function() {
 		$('#secondfinder').typeahead({
    	source: function(query, process) {
        var $url = '/ajax/people/?term=' + query ;
        var $items = new Array;
        $items = [""];
        $.ajax({
            url: $url,
            dataType: "json",
            type: "POST",
            success: function(data) {
                $.map(data, function(data){
                    var group;
                    group = {
                        id: data.id,
                        name: data.term,                            
                        toString: function () {
                            return JSON.stringify(this);
                            //return this.app;
                        },
                        toLowerCase: function () {
                            return this.name.toLowerCase();
                        },
                        indexOf: function (string) {
                            return String.prototype.indexOf.apply(this.name, arguments);
                        },
                        replace: function (string) {
                            var value = '';
                            value +=  this.name;
                            if(typeof(this.level) != 'undefined') {
                                value += ' <span class="pull-right muted">';
                                value += this.level;
                                value += '</span>';
                            }
                            return String.prototype.replace.apply('<div style="padding: 10px; font-size: 1.5em;">' + value + '</div>', arguments);
                        }
                    };
                    $items.push(group);
                });

                process($items);
            }
        });
    },
    property: 'term',
    items: 10,
    minLength: 3,
    updater: function (item) {
        var item = JSON.parse(item);
        $('#finder2ID').val(item.id);       
        return item.name;
    }
});
 		
    });