// JavaScript Document<script type="text/javascript">
$(function(){
	$('.ruler a').click(function() {
		id = $(this).parents('li.ruler').attr('id');
		id = id.replace(/delete-/, "");
		el = $(this);
		$.post('http://localhost/redev/medievalcoins/deletemintruler/id/', { id: id, ajax: 'true' }, function() {
			$(el).parents('li.ruler')
			.animate( { backgroundColor: '#cb5555' }, 500)
			.animate( { height: 0, paddingTop: 0, paddingBottom: 0 }, 500, function() {
				$(this).css( { 'display' : 'none' } );
			});
		});
	});
});