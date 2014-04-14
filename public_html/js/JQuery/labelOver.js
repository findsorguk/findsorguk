/**
 *	JQuery LabelOver plugin by Union Room Ltd - http://www.unionroom.com/
 *	
 *	@author: Jon Park
 *	@version: 1.0.0
 *	@created: May 11th, 2009
 */
jQuery.fn.labelOver = function() {
	var $bound = false;
	this.each(function() {
		var $label = $(this);
		var $target = $('#' + $label.attr('for'));
		
		if (!$bound) {
			var $parent = $label.parents('form').get(0).id;
			$('form#' + $parent).bind('submit', function() {
				$inputs = $('form#' + $parent + ' input');
				$inputs.each(function() { 
					var $theInput = $(this);
					var $myLabel = $('label[for=' + $theInput.attr('id') + ']');
					if ($myLabel.hasClass('overlaid')) {
						if ($theInput.val() == $myLabel.html()) {
							$theInput.val('');
						}
					}
				});
			});
			$bound = true;
		}
		
		$label.css({ width: '0px', height: '0px', overflow: 'hidden', display: 'block' }).addClass('overlaid');
		if ($target.val() == '') { $target.val($label.html()); }
		$target
			.bind('focus', function($e) {
				var $thisLabel = $('label[for=' + $(this).attr('id') + ']');
				if ($(this).val() == $thisLabel.html()) { 
					$(this).val('');
				}
			})
			.bind('blur', function($e) {
				var $thisLabel = $('label[for=' + $(this).attr('id') + ']');
				if ($(this).val() == '') { 
					$(this).val($thisLabel.html());
				}
			});
	});
}
