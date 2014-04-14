// based on http://en.literateprograms.org/Merge_sort_(JavaScript)
// http://en.wikipedia.org/wiki/Merge_sort
// author : triyadhi surahman (tscf.wordpress.com) 2011
// license : MIT
// https://raw.github.com/yadhi/jquery-select-option-sort/master/jquery.selectoptionsort.js

( function ($) {
	
	$.fn.selectOptionSort = function(options) {
		
		// set default options
		var defaults = {
			orderBy: "text",	// text or value
			sort: "asc"			// asc or desc
		}; 
	
		// extend the default to options
		options = $.extend(defaults, options);
		
		return this.each( function() {
			
			// populate array element
			// we need the 0 element as sort value
			var content = new Array();
			var selectedValue = $(this).val();
			
			if (options.orderBy == "value") {
				$(this).children().each( function() {
					var valueText = [
						$(this).val(),
						$(this).text()
					];
					
					content.push(valueText);
				});
			}
			else {
				$(this).children().each( function() {
					var valueText = [
						$(this).text(),
						$(this).val()						
					];
					content.push(valueText);
				});
			}
						
			// sort populated array
			if (options.sort == "desc") {
				var sorted = merge_sort(content, desc);
			}
			else {
				var sorted = merge_sort(content, asc);
			}
			
			// empty the select object
			$(this).empty();
			
			// append element to the select object
			if (options.orderBy == "value") {
				for (var i = 0; i < sorted.length; i++) {
					$(this).append(
						$("<option></option>")
							.val(sorted[i][0])
							.text(sorted[i][1])
					);
				}
			}
			else {
				for (var i = 0; i < sorted.length; i++) {
					$(this).append(
						$("<option></option>")
							.text(sorted[i][0])
							.val(sorted[i][1])
					);
				}
			}
			// attrib selected element.
			$("select[name=" + $(this).context.name + "]").find("option[value=" + selectedValue + "]").attr("selected", true);
		});
		
	};
	
	// callback ascending sort
	var asc = function(left, right) {
		
		if (left[0] == right[0]) {
			return 0;
		}
		else if (left[0] < right[0]) {
			return -1;
		}
		else {
			return 1;
		}
	};
	
	// callback descending sort
	var desc = function(left, right) {
		
		if (left[0] == right[0]) {
			return 0;
		}
		else if (left[0] < right[0]) {
			return 1;
		}
		else {
			return -1;
		}
	};
	
	// merge array
	var merge = function (left,right,comparison) {
		var result = new Array();
		
		while((left.length > 0) && (right.length > 0)) {
			if(comparison(left[0],right[0]) <= 0) {
				result.push(left.shift());
			}					
			else {
				result.push(right.shift());
			}	
		}
		
		while(left.length > 0) {
			result.push(left.shift());
		}
		
		while(right.length > 0) {
			result.push(right.shift());
		}
			
		return result;
	};
	
	// sort and/or merge array
	var merge_sort = function(array,comparison) {
		if(array.length < 2) {
			return array;
		}
		
		var middle = Math.ceil(array.length/2);
		
		return merge(merge_sort(array.slice(0,middle),comparison),
				merge_sort(array.slice(middle),comparison),
				comparison);
	};
	
})(jQuery);