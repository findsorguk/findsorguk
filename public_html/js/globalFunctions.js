/*
Simple JQuery menu.
HTML structure to use:

Notes:

1: each menu MUST have an ID set. It doesn't matter what this ID is as long as it's there.
2: each menu MUST have a class 'menu' set. If the menu doesn't have this, the JS won't make it dynamic

Optional extra classnames:

noaccordion : no accordion functionality
collapsible : menu works like an accordion but can be fully collapsed
expandfirst : first menu item expanded at page load

<ul id="menu1" class="menu [optional class] [optional class]">
<li><a href="#">Sub menu heading</a>
<ul>
<li><a href="http://site.com/">Link</a></li>
<li><a href="http://site.com/">Link</a></li>
<li><a href="http://site.com/">Link</a></li>
...
...
</ul>
<li><a href="#">Sub menu heading</a>
<ul>
<li><a href="http://site.com/">Link</a></li>
<li><a href="http://site.com/">Link</a></li>
<li><a href="http://site.com/">Link</a></li>
...
...
</ul>
...
...
</ul>

Copyright 2008 by Marco van Hylckama Vlieg

web: http://www.i-marco.nl/weblog/
email: marco@i-marco.nl

Free for non-commercial use
*/

function initMenus() {
	$('ul.menu ul').hide();
	$.each($('ul.menu'), function(){
		$('.expandfirst ul:first').show();

		//$('#' + this.id + '.expandfirst ul:first').show();
	});
	$('ul.menu li a').click(
		function() {
			var checkElement = $(this).next();
			var parent = this.parentNode.parentNode.id;

			if($('#' + parent).hasClass('noaccordion')) {
				$(this).next().slideToggle('normal');
				return false;
			}
			if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
				if($('#' + parent).hasClass('collapsible')) {
					$('#' + parent + ' ul:visible').slideUp('normal');
				}
				return false;
			}
			if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
				$('#' + parent + ' ul:visible').slideUp('normal');
				checkElement.slideDown('normal');
				return false;
			}
		}
	);
}
$(document).ready(function() {
    initMenus();
    $('a[rel*=lightbox]').lightBox();
	$('.tipme').tooltip();
    
    $.reject({
		reject: { msie7: true, msie8:true }, 
		 header: 'Your internet browser is not supported by this website',  
		    // Paragraph 1  
		    paragraph1: 'Your browser is out of date, and may not be compatible with '+  
		                'our website. We suggest one of the browsers '+  
		                'found below, but you might need your network administrator to install it.',  
		    close: true, // Allow closing of window  
		    // Message displayed below closing link  
		    closeMessage: '<strong>By ignoring this notice you acknowledge that your experience '+  
		                    'on this website may be degraded</strong>',  
		    closeLink: 'Close This Window', // Text for closing link  
		    closeURL: '#', // Close URL  
		    closeESC: true, // Allow closing of window with esc key  
		closeCookie: true, // Set cookie to remember close for this session
	    // Cookie settings are only used if closeCookie is true  
	    cookieSettings: {  
	        // Path for the cookie to be saved on  
	        // Should be root domain in most cases  
	        path: '/',  
	        // Expiration Date (in seconds)  
	        // 0 (default) means it ends with the current session  
	        expires: 0 
	    },  
	  
	    imagePath: './images/', // Path where images are located  
	    overlayBgColor: '#000', // Background color for overlay  
	    overlayOpacity: 0.5, // Background transparency (0-1)  
	  
	    // Fade in time on open ('slow','medium','fast' or integer in ms)  
	    fadeInTime: 'fast',  
	    // Fade out time on close ('slow','medium','fast' or integer in ms)  
	    fadeOutTime: 'fast',  
	  
	});

	return false;
    

});