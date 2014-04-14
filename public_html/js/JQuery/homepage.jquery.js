$(document).ready(function() {
	// Use this example, or...
	
	$('a[@rel*=lightbox]').lightBox();
	$('#Social').AutoTabs(1, {fxFade: true});
	$('#sub-content').AutoTabs(1, {fxFade: true});
    $('#portfolio').innerfade({ speed: 'slow', timeout: 4000, type: 'sequence', containerheight: '100px' }); 
	$('.fade').	innerfade({ speed: 'slow', timeout: 1000, type: 'sequence', containerheight: '100px' }); 
	jQuery.preload( '#sub-content' );
	jQuery('#mycarousel').jcarousel({scroll: 3,
				animation: 700,size:21
			});
});

	
