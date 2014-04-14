// JavaScript Document
/**
 * jQuery.Preload - Multifunctional preloader
 * Copyright (c) 2008 Ariel Flesler - aflesler(at)gmail(dot)com
 * Dual licensed under MIT and GPL.
 * Date: 3/12/2008
 * @author Ariel Flesler
 * @version 1.0.7
 */
;(function($){var n=$.preload=function(c,d){if(c.split)c=$(c);d=$.extend({},n.defaults,d);var f=$.map(c,function(a){if(!a)return;if(a.split)return d.base+a+d.ext;var b=a.src||a.href;if(typeof d.placeholder=='string'&&a.src)a.src=d.placeholder;if(b&&d.find)b=b.replace(d.find,d.replace);return b||null}),g={loaded:0,failed:0,next:0,done:0,total:f.length};if(!g.total)return m();var h='<img/>',j=d.threshold;while(--j>0)h+='<img/>';h=$(h).load(k).error(k).bind('abort',k).each(l);function k(e){g.found=e.type=='load';g.image=this.src;var a=g.original=c[this.index];g[g.found?'loaded':'failed']++;g.done++;if(d.placeholder&&a.src)a.src=g.found?g.image:d.notFound||a.src;if(d.onComplete)d.onComplete(g);if(g.done<g.total)l(0,this);else{if(h.unbind)h.unbind('load').unbind('error').unbind('abort');h=null;m()}};function l(i,a,b){if($.browser.msie&&g.next&&g.next%n.gap==0&&!b){setTimeout(function(){l(i,a,1)},0);return!1}if(g.next==g.total)return!1;a.index=g.next;a.src=f[g.next++];if(d.onRequest){g.image=a.src;g.original=c[g.next-1];d.onRequest(g)}};function m(){if(d.onFinish)d.onFinish(g)}};n.gap=14;n.defaults={threshold:2,base:'',ext:'',replace:''};$.fn.preload=function(a){n(this,a);return this}})(jQuery);