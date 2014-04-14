

(function($){

   $.fn.linkedSelect = function(url,destination,params) {

      var params = $.extend({

       

      },params);

      var $dest = $(destination);



            $.getJSON(url,{term: $$.val() }, function(j){

               if (j.length > 0) {

                  var options = '<option value="">' +params.firstOption+ '</option>';

                  for (var i = 0; i < j.length; i++) {

                     options += '<option value="' + j[i].id + '">' + j[i].term + '</option>';

                  }

               }

               $dest.removeAttr('disabled')
                    .html(options)
                    .find('option:first')
                    .attr('selected', 'selected');

            }); // end getJSON

       

      }); // end return each

   };  // end function

})(jQuery);