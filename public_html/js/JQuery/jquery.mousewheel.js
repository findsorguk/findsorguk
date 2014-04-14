// JavaScript Document
/* Copyright (c) 2006 Brandon Aaron (brandon.aaron@gmail.com || http://brandonaaron.net)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 *
 * $LastChangedDate: 2007-06-20 16:25:35 -0500 (Wed, 20 Jun 2007) $
 * $Rev: 2125 $
 *
 * Version: 2.2
 */
(function($){$.fn.extend({mousewheel:function(f){if(!f.guid)f.guid=$.event.guid++;if(!$.event._mwCache)$.event._mwCache=[];return this.each(function(){if(this._mwHandlers)return this._mwHandlers.push(f);else this._mwHandlers=[];this._mwHandlers.push(f);var s=this;this._mwHandler=function(e){e=$.event.fix(e||window.event);$.extend(e,this._mwCursorPos||{});var delta=0,returnValue=true;if(e.wheelDelta)delta=e.wheelDelta/120;if(e.detail)delta=-e.detail/3;if(window.opera)delta=-e.wheelDelta;for(var i=0;i<s._mwHandlers.length;i++)if(s._mwHandlers[i])if(s._mwHandlers[i].call(s,e,delta)===false){returnValue=false;e.preventDefault();e.stopPropagation();}return returnValue;};if($.browser.mozilla&&!this._mwFixCursorPos){this._mwFixCursorPos=function(e){this._mwCursorPos={pageX:e.pageX,pageY:e.pageY,clientX:e.clientX,clientY:e.clientY};};$(this).bind('mousemove',this._mwFixCursorPos);}if(this.addEventListener)if($.browser.mozilla)this.addEventListener('DOMMouseScroll',this._mwHandler,false);else this.addEventListener('mousewheel',this._mwHandler,false);else
this.onmousewheel=this._mwHandler;$.event._mwCache.push($(this));});},unmousewheel:function(f){return this.each(function(){if(f&&this._mwHandlers){for(var i=0;i<this._mwHandlers.length;i++)if(this._mwHandlers[i]&&this._mwHandlers[i].guid==f.guid)delete this._mwHandlers[i];}else{if($.browser.mozilla&&!this._mwFixCursorPos)$(this).unbind('mousemove',this._mwFixCursorPos);if(this.addEventListener)if($.browser.mozilla)this.removeEventListener('DOMMouseScroll',this._mwHandler,false);else this.removeEventListener('mousewheel',this._mwHandler,false);else
this.onmousewheel=null;this._mwHandlers=this._mwHandler=this._mwFixCursorPos=this._mwCursorPos=null;}});}});$(window).one('unload',function(){var els=$.event._mwCache||[];for(var i=0;i<els.length;i++)els[i].unmousewheel();});})(jQuery);