/*
*   Syntax Highlighter 2.0 plugin for FCKEditor
*   ========================
*   Copyright (C) 2008  Darren James
*   Email : darren.james@gmail.com
*   URL : http://www.psykoptic.com/blog/
*
*   NOTE:
*   ========================
*   This plugin will add or edit a formatted <pre> tag for FCKEditor
*   To see results on the front end of your website
*   You will need to install SyntaxHighlighter 2.0.x from
*   http://alexgorbatchev.com/wiki/SyntaxHighlighter
*
*
*   This program is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.

*   This program is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.

*   You should have received a copy of the GNU General Public License
*   along with this program.  If not, see <http:*www.gnu.org/licenses/>.

*   This program comes with ABSOLUTELY NO WARRANTY.
*/


// Register the related command.

/*
NOTE - Values are case sensitive
- syntaxhighlight2: name of the plugin and directory name (must be the same!)
- SyntaxHighLight2: Name of command used to identify the new toolbar button
*/

FCKCommands.RegisterCommand('SyntaxHighLight2', new FCKDialogCommand('SyntaxHighLight2', FCKLang.DlgSyntaxhighlightTitle, FCKPlugins.Items['syntaxhighlight2'].Path + 'dialog/fck_syntaxhighlight.html', 500, 500));

// Create the "SyntaxHighLight" toolbar button.
var oSyntaxhighlightItem = new FCKToolbarButton('SyntaxHighLight2', FCKLang.SyntaxhighlightBtn);
oSyntaxhighlightItem.IconPath = FCKPlugins.Items['syntaxhighlight2'].Path + 'images/syntaxhighlight.gif';

FCKToolbarItems.RegisterItem('SyntaxHighLight2', oSyntaxhighlightItem);


