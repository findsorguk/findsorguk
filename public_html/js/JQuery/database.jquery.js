function initMenus() {
$('ul.menu ul').hide();
	  $.each($('ul.menu'), function(){
	  $('#' + this.id + '.expandfirst ul:first').show();

	  });
	  $('ul.menu li a').click(
	  function() {
	  var checkElement = $(this).next();
	  var parent = this.parentNode.parentNode.id;
	 

	  if($('#' + parent).hasClass('noaccordion')) {
	  $(this).next().slideToggle('normal');
	  return false;
	  }
	  if((checkElement.is('ul')) &amp;&amp; (checkElement.is(':visible'))) {

	  if($('#' + parent).hasClass('collapsible')) {
	 $('#' + parent + ' ul:visible').slideUp('normal');
	  }
	  return false;
	  }
	  if((checkElement.is('ul')) &amp;&amp; (!checkElement.is(':visible'))) {

	  $('#' + parent + ' ul:visible').slideUp('normal');
	  checkElement.slideDown('normal');
	  return false;
	  }
	  }
	  );

	  }
	  $(document).ready(function() {initMenus();});
	
