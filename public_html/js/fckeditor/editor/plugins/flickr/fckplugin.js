// Create a command.
var flickrCommand = function() {
	//?
}
flickrCommand.prototype.Execute=function() {
	//?
}
flickrCommand.GetState=function() {
	return FCK_TRISTATE_OFF; //we dont want the button to be toggled
}
flickrCommand.Execute=function() {
	var width = 800;
	var height = 440;
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	window.open(FCKConfig.PluginsPath + 'flickr/fck_flickr.php', 'winPopupeditor', 'width='+width+',height='+height+',top='+top+',left='+left+',resizable=yes,scrollbars=yes');
}

// Register the command
FCKCommands.RegisterCommand('flickrCommand', flickrCommand ); //otherwise our command will not be found

// Create the "Popeditor" toolbar button.
var oFlickr = new FCKToolbarButton( 'flickrCommand', FCKLang.btnTitle ) ;
oFlickr.IconPath = FCKConfig.PluginsPath + 'flickr/toolbar_icon.gif' ;  //specifies the image used in the toolbar

// Register the toolbar button with the command
FCKToolbarItems.RegisterItem( 'Flickr', oFlickr ) ; // 'Popeditor' is the name used in the Toolbar config.