// Created by Andreas Lagerkvist http://exscale.se
jQuery.fn.AutoTabs = function(start, settings, callback)
{
	return this.each(function()
	{
		var t = jQuery(this);

		// Generate an un-ordered list based on all divs in the container
		var list = '<ul>';
		// '> div' should not be hard-coded, but retrieved from the settings-argument
		// just like tabs() does it... can't be arsed tho...
		t.find('> div').each(function()
		{
			var t2 = jQuery(this);

			// Get the id and title of the div
			// The title is generated either from the div's title, first-heading or id
			var id = t2.attr('id');
			var title = t2.attr('title');
			if(title == null)
			{
				var title = t2.find('> h1:first-child, > h2:first-child, > h3:first-child, > h4:first-child, > h5:first-child, > h6:first-child').text();
			}
			if(title == null)
			{
				title = id;
				title = title.replace(/-/, " ");
				title = title.substr(0, 1).toUpperCase() + title.substr(1, title.length);
			}
			list += '<li><a href="#' +id +'">' +title +'</a></li>';
		});
		list += '</ul>';

		// Insert the list before the first div in the container
		// (same here, '> div' should probably not be hard-coded
		t.find('> div').eq(0).before(list);

		// Run tabs()...
		t.tabs(start, settings, callback);
	});
}