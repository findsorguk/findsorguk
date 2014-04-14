/*
* Copyright (C) 2009 Joel Sutherland
* Licenced under the MIT license
* http://www.newmediacampaigns.com/page/jcaption-a-jquery-plugin-for-simple-image-captions
*/
(function($) {
	$.fn.jcaption = function(settings) {
		settings = $.extend({
			wrapperElement: 'div',
			wrapperClass: 'caption',
			captionElement: 'p',
			imageAttr: 'alt',
			requireText: true,
			copyStyle: false,
			removeStyle: true,
			removeAlign: true,
			copyAlignmentToClass: false,
			copyFloatToClass: true,
			autoWidth: true,
			animate: false,
			show: {opacity: 'show'},
			showDuration: 200,
			hide: {opacity: 'hide'},
			hideDuration: 200	
		}, settings);

		return $(this).each(function(){
			//Only add the caption after the image has been loaded.  This makes sure we can know the width of the caption.
			
			$(this).bind('load', function(){
				
				//Make sure the captioning isn't applied twice when the IE fix at the bottom is applied
				if($(this).data('loaded')) return false;
				$(this).data('loaded', true);
			
				//Shorthand for the image we will be applying the caption to
				var image = $(this);
				
				//Only create captions if there is content for the caption
				if(image.attr(settings.imageAttr).length > 0 || !settings.requireText){
					
					//Wrap the image with the caption div
					image.wrap("<" + settings.wrapperElement + " class='" + settings.wrapperClass + "'></" + settings.wrapperElement + ">");
					
					//Save Image Float
					var imageFloat = image.css('float')
					
					//Save Image Style
					var imageStyle = image.attr('style');
					if(settings.removeStyle) image.removeAttr('style');
					
					//Save Image Align
					var imageAlign = image.attr('align');
					if(settings.removeAlign) image.removeAttr('align');
					
					//Put Caption in the Wrapper Div
					var div = $(this).parent().append('<' + settings.captionElement + '>' + image.attr(settings.imageAttr) + '</' + settings.captionElement + '>');
					
					if(settings.animate){
						$(this).next().hide();
						$(this).parent().hover(
						function(){
							$(this).find('p').animate(settings.show, settings.showDuration);
						},
						function(){
							$(this).find('p').animate(settings.hide, settings.hideDuration);
						});
					}
					
					//Copy Image Style to Div
					if(settings.copyStyle) div.attr('style',imageStyle);
					
					//If there is an alignment on the image (for example align="left") add "left" as a class on the caption.  This helps deal with older Text Editors like TinyMCE
					if(settings.copyAlignmentToClass) div.addClass(imageAlign);
					
					//Transfers the float style from the image to the caption container
					if(settings.copyFloatToClass) div.addClass(imageFloat);
					
					//Properly size the caption div based on the loaded image's size
					if(settings.autoWidth) div.width(image.width());
				}
			});
			
			// Thanks to Captify for this bit!
			//if the image has already loaded (due to being cached), force the load function to be called
			if (this.complete || this.naturalWidth > 0){
				$(this).trigger('load');
			}
		});
	}
})(jQuery);
