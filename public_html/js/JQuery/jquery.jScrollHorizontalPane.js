/*
 * Copyright (c) 2008 Threeformed Media (http://www.threeformed.com)
 * This is licensed under GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * 
 * 
 * *******
 * 
 * This plugin is derived in part from JScrollPane created by Kevin Luck(http://www.kelvinluck.com)
 * 
 * Copyright (c) 2006 Kelvin Luck (kelvin AT kelvinluck DOT com || http://www.kelvinluck.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) 
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * 
 * See http://kelvinluck.com/assets/jquery/jScrollPane/
 * jQueryId: jScrollPane.js 3125 2007-09-06 20:39:42Z kelvin.luck jQuery
 */

/**
 * Replace the default horizontal scroll bars on matched 
 * elements with a CSS styled veresion.  Very similar to the JScrollPane 
 * which does vertical scrolling, 2 features in particular have been added. 
 * 
 * 1) Intervals
 * 2) Resizing
 * 
 * 1) Intervals can be added by attaching a class type of "scroll-interval" to any 
 * element wrapped within the jscrollhorizontalpane context.  This provides
 * the following abilitiies: 
 * 			a) When dragging, it will snap to the closest element on release of dragger.
 * 			b) Mousewheel motions jump between intervals
 * 			c) Notches appear by default on the scrollbar, but can be overriden by css.
 * 
 * 2) Resizing Also occurs. When turned on, all widths are dealt in percentages, so on a 
 * screen refresh, the scroller will resize itself based on it's initial percentage.
 * There are a ton of different circumstances that need to be accounted for, and i'm sure 
 * it's not meeting some people's expected behaviour so let me know about any problems or 
 * feature requests for that!  The resizing is done through the WResize plugin.
 * 
 *
 * @example jQuery(".scroll-pane").jScrollHorizontalPane();
 *
 * @name jScrollHorizontalPane
 * @type jQuery
 * @param Object	settings	hash with options, described below.
 *								scrollbarHeight	-	The height of the generated scrollbar in pixels
 *								scrollbarMargin	-	The amount of space to leave on the side of the scrollbar in pixels
 *								wheelSpeed		-	The speed the pane will scroll in response to the mouse wheel in pixels
 *								showArrows		-	Whether to display arrows for the user to scroll with
 *								arrowSize		-	The height of the arrow buttons if showArrows=true
 *								animateTo		-	Whether to animate when calling scrollTo and scrollBy
 *								dragMinWidth	-	The minimum width to allow the drag bar to be
 *								dragMaxWidth	-	The maximum width to allow the drag bar to be
 *								animateInterval	-	The interval in milliseconds to update an animating scrollHorizontalPane (default 100)
 *								animateStep		-	The amount to divide the remaining scroll distance by when animating (default 3)
 *								maintainPosition-	Whether you want the contents of the scroll pane to maintain it's position when you re-initialise it - so it doesn't scroll as you add more content (default true)
 *								resize			- 	Whether or not to have resizing turned on or not.
 * 								minimumWidth    - 	The minimum width to allow the jScrollHorizontalPane to be resized to.  Only effective when resize is on.
 * 								reset			-	When set to 'true' all the global properties will be reset.  This is useful for dynamic refreshes on the page.
 * @return jQuery
 * @cat Plugins/jScrollHorizontalPane
 * @author Threeformed Media ( www.threeformed.com, info@threeformed.com )
 * @version 1.0.0
 */

var _jscr_originalSizes = new Array();
var _jscr_differenceSizes = new Array();
var _jscr_previousWindowSize = new Array();
var _jscr_originalPercentages = new Array();
var _jscr_intervals = new Array();
var _jscr_trackInt = new Array();
var _jscr_originalPos = new Array();
var _jscr_globalProperties = new Array();

jQuery.jScrollHorizontalPane = {
	active : []
};

jQuery.fn.jScrollHorizontalPane = function(settings)
{
	settings = jQuery.extend(
		{
			scrollbarHeight : 10,
			scrollbarMargin : 5,
			wheelSpeed : 18,
			showArrows : false,
			arrowSize : 10,
			animateTo : false,
			dragMinWidth : 1,
			dragMaxWidth : 9999,
			animateInterval : 20,
			animateStep: 3,
			maintainPosition: true, 
			resize: true,
			minimumWidth: 200,
			reset: false
		}, settings
	);
	
	return this.each(
		function()
		{
			if(settings.reset == true) {
				jQuery.fn.jScrollHorizontalPane.reset();
			}
			
			//This holds each one of the intervals, defaulting with one at the beginning.
			var $this = jQuery(this);			
			var mouseWheelNext = 0;
			var mouseWheelMove = false;
			var currentId = $this.attr('id');
			
			if(currentId == undefined) {
				currentId = $this.attr('class');
			}
			
			var previousWindow = _jscr_previousWindowSize[currentId];
			_jscr_originalPos[currentId] = -1;
			_jscr_globalProperties[currentId] = settings;
			_jscr_previousWindowSize[currentId] = jQuery(window).width();

			//On initial load, set values needed for percentage resizing.
			if(_jscr_originalSizes[currentId] == undefined) {		

				//ie6 hack, since jquery width doesnt get the right value on an inproper refresh
				if((jQuery.browser.msie) && (parseInt(jQuery.browser.version) == 6)) {
					var outerWidth = parseInt($this.outerWidth()) - parseInt($this.offset().left);
					_jscr_differenceSizes[currentId] = $this.offset().left / jQuery(window).width();
				} else {
					var outerWidth = $this.outerWidth();
					_jscr_differenceSizes[currentId] = $this.position().left / jQuery(window).width();
				}

				percentageWidth = (outerWidth / jQuery(window).width());			
				_jscr_originalPercentages[currentId] = percentageWidth;
				_jscr_originalSizes[currentId] = jQuery(window).width();
			} else { 
				percentageWidth = _jscr_originalPercentages[currentId];
				diff = _jscr_differenceSizes[currentId] - (($this.offset().left + _jscr_originalPos[currentId])/ jQuery(window).width());
				percentageWidth = percentageWidth + diff;
			}

			var halfIntervals = new Array();
			_jscr_intervals = new Array();
			halfIntervals[0] = 0;
			_jscr_intervals[0] = 0;
			margin = $this.position().left;
			offset = 1;
			
			if(margin < 0) {
				margin = 0;
			}
			
			//Handles interval code
			jQuery(".scroll-interval", $this).each(
				function(i, elem) {
					pos = jQuery(elem).position().left - margin;
					if(pos != 0) {
						_jscr_intervals[i+offset] = pos;
					} else { 
						offset--;
					}
				}
			);
				
			if(_jscr_intervals.length <= 1) { 
				_jscr_intervals = new Array();
			}

			if (jQuery(this).parent().is('.jScrollPaneContainer')) {
				var currentScrollPosition = settings.maintainPosition ? $this.offset({relativeTo:jQuery(this).parent()[0]}).left : 0;
				var $c = jQuery(this).parent();
				var paneWidth = $c.outerWidth();
				var paneHeight = $c.innerHeight();
				var rightPos = $this.offset().left + _jscr_originalPos[currentId] + paneWidth;
				
				if((previousWindow != jQuery(window).width()) && ((rightPos > jQuery(window).width()) || (previousWindow < jQuery(window).width())) && (settings.resize == true)) {

					if(jQuery(window).width() >= _jscr_originalSizes[currentId]) {
						paneWidth = (jQuery(window).width() *  percentageWidth);
					} else {
						//Give the outside edge a 10 px buffer margin
						paneWidth = jQuery(window).width() - ($this.offset().left + _jscr_originalPos[currentId]) - 10;
					}

					if(paneWidth < settings.minimumWidth){
						paneWidth = settings.minimumWidth;
					}

					jQuery(this).parent().css(
									{ 
										'height':paneHeight+'px', 
										'width': paneWidth + 'px'
									}
								);
				}
				
				var trackWidth = paneWidth;

				if($c.unmousewheel) {
						
					if(jQuery.browser.opera) {
						$c.unbind("mousewheel", fn = function() { });	
					} else {
						$c.unmousewheel();
					}				
				}

				jQuery('>.jScrollPaneTrack, >.jScrollArrowLeft, >.jScrollArrowRight', $c).remove();
				$this.css({'left':0});
				_jscr_originalPos[currentId] = -1;
			} else {
				var currentScrollPosition = 0;
				this.originalPadding = $this.css('paddingTop') + ' ' + $this.css('paddingRight') + ' ' + $this.css('paddingBottom') + ' ' + $this.css('paddingLeft');
				this.originalSidePaddingTotal = (parseInt($this.css('paddingLeft')) || 0) + (parseInt($this.css('paddingRight')) || 0);
				var paneWidth = $this.outerWidth();
				var rightPos = $this.offset().left + _jscr_originalPos[currentId] + paneWidth;

				if((rightPos) > jQuery(window).width()) { 
					paneWidth = jQuery(window).width() *  percentageWidth; 
				}
				
				if(paneWidth < settings.minimumWidth){
					paneWidth = settings.minimumWidth;
				}
					
				var paneHeight = $this.innerHeight();
				var trackWidth = paneWidth;
				
				$this.wrap(
					jQuery('<div></div>').attr(
						{'className':'jScrollPaneContainer'}
					).css(
						{
							'height':paneHeight+'px', 
							'width':paneWidth+'px'
						}
					)
				);
				// deal with text size changes (if the jquery.em plugin is included)
				// and re-initialise the scrollPane so the track maintains the
				// correct size
				jQuery(document).bind(
					'emchange', 
					function(e, cur, prev)
					{
						$this.jScrollHorizontalPane(settings);
					}
				);
			}
			var p = this.originalSidePaddingTotal;
			
			$this.css(
				{
					'height': paneHeight - settings.scrollbarHeight - p + 'px',
					'width': 'auto',
					'paddingRight':settings.scrollbarMargin + 'px'
				}
			);

			var contentWidth = $this.outerWidth();

			//ie6 and 7, outside width does not always guarantee the full size of the div
			//is returned for outerWidth
			if(jQuery.browser.msie || jQuery.browser.opera || jQuery.browser.safari) {
				var ieWidth = 0;
				$this.children().each(function(i, elem) { if(jQuery(elem).outerWidth() > ieWidth) { ieWidth = jQuery(elem).outerWidth();}});
				if(ieWidth > contentWidth) {
					contentWidth = ieWidth;
				}				
			}

			var percentInView = paneWidth / contentWidth;
			var trackIntervals = new Array();

			if (percentInView < 0.99) {
				var $container = $this.parent();

				$container.append(
					jQuery('<div></div>').attr({'className':'jScrollPaneTrack'}).css({'height':settings.scrollbarHeight+'px'}).append(
						jQuery('<div></div>').attr({'className':'jScrollPaneDrag'}).css({'height':settings.scrollbarHeight+'px'}).append(
							jQuery('<div></div>').attr({'className':'jScrollPaneDragLeft'}).css({'height':settings.scrollbarHeight+'px'}),
							jQuery('<div></div>').attr({'className':'jScrollPaneDragRight'}).css({'height':settings.scrollbarHeight+'px'})
						)
					)
				);
				
				var $track = jQuery('>.jScrollPaneTrack', $container);
			
				//Attach the intervals to the track
				for(inter in _jscr_intervals) { 
					
					if(settings.showArrows == true) { 
						scrollOffset = settings.arrowSize;
					} else { 
						scrollOffset = 0;
					}

					intervalTrackPos = _jscr_intervals[inter] / contentWidth * $track.width() - (scrollOffset);
					trackIntervals[inter] = intervalTrackPos;
					
					if(trackIntervals[inter - 1] != undefined) {
						halfIntervals[inter-1] = (trackIntervals[inter] + trackIntervals[inter-1]) / 2;
					}
				
					if(inter != 0) { 
						interObj = jQuery('<div><!-- | --></div>').attr({'className':'jScrollIntervalTrack'}).css({'left':intervalTrackPos + 'px'})
						$track.append(interObj);
					}
				}

				var $drag = jQuery('>.jScrollPaneTrack .jScrollPaneDrag', $container);
				
				if (settings.showArrows) {
					
					var currentArrowButton;
					var currentArrowDirection;
					var currentArrowInterval;
					var currentArrowInc;
					var whileArrowButtonDown = function()
					{
						if (currentArrowInc > 4 || currentArrowInc%4==0) {
							positionDrag(dragPosition + currentArrowDirection * mouseWheelMultiplier);
						}
						currentArrowInc ++;
					};
					var onArrowMouseUp = function(event)
					{
						jQuery('body').unbind('mouseup', onArrowMouseUp);
						currentArrowButton.removeClass('jScrollActiveArrowButton');
						clearInterval(currentArrowInterval);
						arrowUp = true;
						moveIntervals();
					};
					var onArrowMouseDown = function() {
						jQuery('body').bind('mouseup', onArrowMouseUp);
						currentArrowButton.addClass('jScrollActiveArrowButton');
						currentArrowInc = 0;
						whileArrowButtonDown();
						currentArrowInterval = setInterval(whileArrowButtonDown, 20);
					};
					$container
						.append(
							jQuery('<a></a>')
								.attr({'href':'javascript:;', 'className':'jScrollArrowLeft'})
								.css({'width':settings.arrowSize+'px'})
								.html('Scroll Left')
								.bind('mousedown', function()
								{
									currentArrowButton = jQuery(this);
									currentArrowDirection = -1;
									onArrowMouseDown();
									this.blur();
									return false;
								}),
							jQuery('<a></a>')
								.attr({'href':'javascript:;', 'className':'jScrollArrowRight'})
								.css({'width':settings.arrowSize+'px'})
								.html('Scroll Right')
								.bind('mousedown', function()
								{
									currentArrowButton = jQuery(this);
									currentArrowDirection = 1;
									onArrowMouseDown();
									this.blur();
									return false;
								})
						);
					if (settings.arrowSize) {
						trackWidth = paneWidth - settings.arrowSize - settings.arrowSize;
						$track
							.css({'width': trackWidth+'px', left:settings.arrowSize+'px'})
					} else {
						var leftArrowWidth = jQuery('>.jScrollArrowLeft', $container).width();
						settings.arrowSize = leftArrowWidth;
						trackWidth = paneWidth - leftArrowWidth - jQuery('>.jScrollArrowRight', $container).width();
						$track
							.css({'width': trackWidth +'px', left: leftArrowWidth+'px'})
					}
				}
				
				var $pane = jQuery(this).css({'position':'absolute', 'overflow':'visible'});
				
				var currentOffset;
				var maxX;
				var mouseWheelMultiplier;
				
				// store this in a seperate variable so we can keep track more accurately than just updating the css property..
				var dragPosition = 0;
				var dragMiddle = percentInView*paneWidth/2;
				
				// pos function borrowed from tooltip plugin and adapted...
				var getPos = function (event, c) {
					var p = c == 'X' ? 'Left' : 'Bottom';
					return event['page' + c] || (event['client' + c] + (document.documentElement['scroll' + p] || document.body['scroll' + p])) || 0;
				};
				
				var ignoreNativeDrag = function() {	return false; };
				var currentInterval = 0;
				var direction = 1;
				var arrowUp = false;
				var intervalMove = false;
				_jscr_trackInt[currentId] = -1;;
						
				var initDrag = function()
				{
					ceaseAnimation();
					currentOffset = $drag.offset(false);
					currentOffset.left -= dragPosition;
					maxX = trackWidth - $drag[0].offsetWidth;
					mouseWheelMultiplier = 2 * settings.wheelSpeed * maxX / contentWidth;
				};
				
				var onStartDrag = function(event)
				{
					initDrag();
					dragMiddle = getPos(event, 'X') - dragPosition - currentOffset.left;
					jQuery('body').bind('mouseup', onStopDrag).bind('mousemove', updateScroll);
					if (jQuery.browser.msie) {
						jQuery('body').bind('dragstart', ignoreNativeDrag).bind('selectstart', ignoreNativeDrag);
					}
					return false;
				};
				var onStopDrag = function()
				{
					jQuery('body').unbind('mouseup', onStopDrag).unbind('mousemove', updateScroll);
					dragMiddle = percentInView*paneWidth/2;
					moveIntervals();
					
					if (jQuery.browser.msie) {
						jQuery('body').unbind('dragstart', ignoreNativeDrag).unbind('selectstart', ignoreNativeDrag);
					}
				};
				var positionDrag = function(destX)
				{
					//Figure out if we need to adjust because of intervals.
					evaluateIntervals(dragPosition, destX);
					destX = destX < 0 ? 0 : (destX > maxX ? maxX : destX);
					dragPosition = destX;

					$drag.css({'left':destX +'px'});
					var p = destX / maxX;
					_jscr_originalPos[currentId] = (paneWidth-contentWidth) * p * -1;
					$pane.css({'left':((paneWidth-contentWidth)*p) + 'px'});
					$this.trigger('scroll');
				};
				
				var updateScroll = function(e)
				{
					positionDrag(getPos(e, 'X') - currentOffset.left - dragMiddle);
				};
				
				var evaluateIntervals = function(position, destX) { 
	
					if((intervalMove == false) && (mouseWheelMove != true)) { 
							_jscr_trackInt[currentId] = -1;
							halfInter = -1;
							
							smallInter = -1;
							bigInter = -1;
							
							endDragPos = destX + $drag.width();
							fullTrackWidth = jQuery('.jScrollPaneTrack').width();

							for(inter in trackIntervals) { 
								if((endDragPos >= fullTrackWidth) && (endDragPos >= trackIntervals[inter])) {
									_jscr_trackInt[currentId] = inter;
								} else if(destX >= trackIntervals[inter]) {
									smallInter = inter;
								}	else { 
									bigInter = inter;
									break;
								}
								
							}

							if(_jscr_trackInt[currentId] == -1) {
								smallDistance = destX - trackIntervals[smallInter];
								largeDistance = trackIntervals[bigInter] - destX;
								
								if(smallDistance <= largeDistance) {
									_jscr_trackInt[currentId] = smallInter;
								} else { 
									_jscr_trackInt[currentId] = bigInter;
								}
							}
					} else { 
						intervalMove = false;
					}
	
				}
				
				var moveIntervals = function() { 
					if(_jscr_trackInt[currentId] != -1) { 
						//Catching arrow clicks
						if(arrowUp == true) { 
							if((direction == -1) && (_jscr_trackInt[currentId] != 0)) { 
								_jscr_trackInt[currentId] = currentInterval - 1;
							} else if((direction == 1) && (_jscr_trackInt[currentId] != (_jscr_intervals.length -1))) {
								_jscr_trackInt[currentId] = parseInt(currentInterval) + 1;
							}
							arrowUp = false;
						} 
						
						intervalMove = true;
						positionDrag(trackIntervals[_jscr_trackInt[currentId]]);
						currentInterval = _jscr_trackInt[currentId];
					}
				}
				
				var arrowSize = 0;
				
				if(settings.showArrows == true) {
					arrowSize = settings.arrowSize;
				} 
				
				var dragH = Math.max(Math.min(percentInView*(paneWidth-arrowSize*2), settings.dragMaxWidth), settings.dragMinWidth);
				
				$drag.css(
					{'width':dragH+'px'}
				).bind('mousedown', onStartDrag);
				
				var trackScrollInterval;
				var trackScrollInc;
				var trackScrollMousePos;
				var doTrackScroll = function()
				{
					if (trackScrollInc > 8 || trackScrollInc%4==0) {
						positionDrag((dragPosition - ((dragPosition - trackScrollMousePos) / 2)));
					}
					trackScrollInc ++;
				};
				var onStopTrackClick = function()
				{
					clearInterval(trackScrollInterval);
					moveIntervals();
					jQuery('body').unbind('mouseup', onStopTrackClick).unbind('mousemove', onTrackMouseMove);
				};
				var onTrackMouseMove = function(event)
				{
					trackScrollMousePos = getPos(event, 'X') - currentOffset.left - dragMiddle;
				};
				var onTrackClick = function(event)
				{
					initDrag();
					onTrackMouseMove(event);
					trackScrollInc = 0;
					jQuery('body').bind('mouseup', onStopTrackClick).bind('mousemove', onTrackMouseMove);
					trackScrollInterval = setInterval(doTrackScroll, 100);
					doTrackScroll();
				};
				
				$track.bind('mousedown', onTrackClick);
				
				// if the mousewheel plugin has been included then also react to the mousewheel
				if ($container.mousewheel) {

					$container.mousewheel (
						function (event, delta) {
							var movePos = -1;
							
							if(jQuery.browser.opera) {
								delta = event.wheelDelta / 120;	
							}

							//The following handles intervals with the mouse wheel
							if(trackIntervals.length > 1) {
								mouseWheelMove = true;
										
								//increase or decrease the interval we are currently on, depending
								//on the direction of the mouse wheel
								if(delta < 0) {
									_jscr_trackInt[currentId] = parseInt(_jscr_trackInt[currentId]) + 1;

									if((_jscr_trackInt[currentId]) >= trackIntervals.length - 1) {
										_jscr_trackInt[currentId] = trackIntervals.length - 1;
									}
									
									//If the next interval is beyond the dragWidth then recalculate.
									if((parseInt($drag.width())+ parseInt(trackIntervals[_jscr_trackInt[currentId]])) > parseInt(jQuery('.jScrollPaneTrack').width())) {
										movePos = parseInt(jQuery('.jScrollPaneTrack').width()) - $drag.width();
									}
									
								} else { 
									_jscr_trackInt[currentId] = parseInt(_jscr_trackInt[currentId]) - 1;
									if(_jscr_trackInt[currentId] < 0) {
										_jscr_trackInt[currentId] = 0;
									}
								}
							}

							initDrag();
							ceaseAnimation();
							var d = dragPosition;
							
							//when intervals are in use, mouseWheelMove is set to true
							if(mouseWheelMove == true) {
								if(movePos == -1) {
									positionDrag(trackIntervals[_jscr_trackInt[currentId]]);
								} else {
									positionDrag(movePos);
								}
							} else {
								positionDrag(dragPosition - delta * mouseWheelMultiplier);
							}
							
							moveIntervals();
							var dragOccured = d != dragPosition;
							mouseWheelMove = false;
							return !dragOccured;
						},
						false
					);					
				}
				var _animateToPosition;
				var _animateToInterval;
				function animateToPosition()
				{

					var diff = (_animateToPosition - dragPosition) / settings.animateStep;
					
					if ((diff > 1 || diff < -1) && ((dragPosition + diff + $drag.width()) < (paneWidth))) {
						positionDrag(dragPosition + diff);
					} else {
						positionDrag(_animateToPosition);
						ceaseAnimation();
					}
				}
				var ceaseAnimation = function()
				{
					if (_animateToInterval) {
						clearInterval(_animateToInterval);
						delete _animateToPosition;
					}
				};
				var scrollTo = function(pos, preventAni)
				{
					if (typeof pos == "string") {
						$e = jQuery(pos, this);

						if (!$e.length) return;
						pos = $e.position().left;
					}

					ceaseAnimation();
					var destDragPosition = -pos/(paneWidth-contentWidth) * maxX;
					if (!preventAni || settings.animateTo) {
						_animateToPosition = destDragPosition;
						_animateToInterval = setInterval(animateToPosition, settings.animateInterval);
		
					} else {
						positionDrag(destDragPosition);
					}	
				};
				$this[0].scrollTo = scrollTo;
				
				$this[0].scrollBy = function(delta)
				{
					var currentPos = -parseInt($pane.css('left')) || 0;
					scrollTo(currentPos + delta);
				};
				
				initDrag();
				
				scrollTo(-currentScrollPosition, true);
				
				jQuery.jScrollHorizontalPane.active.push($this[0]);

			} else {
				var scrollTo = function(pos, preventAni) {}
				$this[0].scrollTo = scrollTo;
				
				$this.css(
					{
						'height':paneHeight-this.originalSidePaddingTotal+'px',
						'width': paneWidth+'px',
						'padding':this.originalPadding
					}
				);
				// remove from active list?
			}
			
		}
	)
};

jQuery.fn.jScrollHorizontalPane.reset = function() {
	_jscr_originalSizes = new Array();
	_jscr_differenceSizes = new Array();
	_jscr_previousWindowSize = new Array();
	_jscr_originalPercentages = new Array();
	_jscr_intervals = new Array();
	_jscr_trackInt = new Array();
	_jscr_originalPos = new Array();
	_jscr_globalProperties = new Array();
}

// clean up the scrollTo expandos
jQuery(window)
	.bind('unload', function() {
		var els = jQuery.jScrollHorizontalPane.active; 
		for (var i=0; i<els.length; i++) {
			els[i].scrollTo = els[i].scrollBy = null;
		}
	}
);
	
	
/*   
=============================================================================== 
WResize is the jQuery plugin for fixing the IE window resize bug 
............................................................................... 
                                               Copyright 2007 / Andrea Ercolino 
------------------------------------------------------------------------------- 
LICENSE: http://www.opensource.org/licenses/mit-license.php 
WEBSITE: http://noteslog.com/ 

------------------------------------------------------------------------------- 
USAGE: (div is automatically resized when the window is resized)
------------------------------------------------------------------------------- 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" style="overflow:hidden;"> 
<head> 
<title> test window resize </title> 
 
<script type="text/javascript" src="http://jquery.com/src/jquery-latest.pack.js"></script> 
<script type="text/javascript" src="jquery.wresize.js"></script> 
 
 
<script type="text/javascript"> 
jQuery( function( jQuery )  
{ 
    function content_resize()  
    { 
        var w = jQuery( window ); 
        var H = w.height(); 
        var W = w.width(); 
        jQuery( '#content' ).css( {width: W-20, height: H-20} ); 
    } 
 
    jQuery( window ).wresize( content_resize ); 
 
    content_resize(); 
} ); 
</script> 
 
</head> 

<body> 
  
<div id="content" style="border: 1px dashed silver; position:absolute; overflow:auto;"> 
test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test test  
</div> 
 
</body> 
</html>

=============================================================================== 
*/ 
 
( function( jQuery )  
{ 
 
	jQuery(function(jQuery) {
		jQuery( window ).wresize(resizeScroller);
		
		function resizeScroller() {
		jQuery('.scroll-pane').each(function(i, elem) { 	
			
			if(jQuery(elem).attr('id') == undefined) {
				id = jQuery(elem).attr('class');
			} else {
				id = jQuery(elem).attr('id');
			}
			jQuery(elem).jScrollHorizontalPane(_jscr_globalProperties[jQuery(elem).attr('id')]);
		});
		}	
	});


    jQuery.fn.wresize = function( f )  
    { 
        version = '1.1'; 
        wresize = {fired: false, width: 0}; 
 
        function resizeOnce()  
        { 
            if ( jQuery.browser.msie ) 
            { 
                if ( ! wresize.fired ) 
                { 
                    wresize.fired = true; 
                } 
                else  
                { 
                    var version = parseInt( jQuery.browser.version, 10 ); 
                    wresize.fired = false; 
                    if ( version < 7 ) 
                    { 
                        return false; 
                    } 
                    else if ( version == 7 ) 
                    { 
                        //a vertical resize is fired once, an horizontal resize twice 
                        var width = jQuery( window ).width(); 
                        if ( width != wresize.width ) 
                        { 
                            wresize.width = width; 
                            return false; 
                        } 
                    } 
                } 
            }
 
            return true; 
        } 
 
        function handleWResize( e )  
        { 
			if ( resizeOnce() ) 
            { 
                return f.apply(this, [e]); 
            } 
        } 
 
        this.each( function()  
        { 
            if ( this == window ) 
            { 
                jQuery( this ).resize( handleWResize ); 
            } 
            else 
            { 
                jQuery( this ).resize( f ); 
            } 
        } ); 
 
        return this; 
    }; 
 
} ) ( jQuery );