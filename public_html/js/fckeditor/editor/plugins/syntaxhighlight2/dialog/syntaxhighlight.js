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

var version = "2.1.0";
var dialog = window.parent; // IE7 needs this
var oEditor = window.parent.InnerDialogLoaded();
var FCK = oEditor.FCK;
var FCKLang = oEditor.FCKLang;
var FCKConfig = oEditor.FCKConfig;
var FCKTools = oEditor.FCKTools;
var FCKBrowserInfo = oEditor.FCKBrowserInfo;


// default syntax object
function CodeSyntax() {
    var oCodeSyntax = new Object();
    oCodeSyntax.Code = oContainerPre.innerHTML;
    oCodeSyntax.Advanced = false;
    oCodeSyntax.Gutter = false;
    oCodeSyntax.NoControls = false;
    oCodeSyntax.Collapse = false;
    oCodeSyntax.Firstline = 0;
    oCodeSyntax.Showcolumns = false;
    oCodeSyntax.Highlight = null;

    return oCodeSyntax;
}

var oContainerPre = FCK.Selection.MoveToAncestorNode('PRE');
var oCodeSyntax = null;

// ----------------------
// populate our oCodeSyntax object
if (oContainerPre) {
    if (oContainerPre.tagName == 'PRE' && GetAttribute(oContainerPre, 'title') == 'code') {

        var CodeSettings = GetAttribute(oContainerPre, 'class', '');
        if (CodeSettings.length > 0) {

            // found valid code snippet, populate our CodeSyntax object
            oCodeSyntax = new CodeSyntax();

            if (CodeSettings.indexOf(";") > -1) {
                // advanced options set

                oCodeSyntax.Advanced = true;
                oCodeSyntax.Lang = CodeSettings.substring(CodeSettings.indexOf(":") + 1, CodeSettings.indexOf(";")).replace(/^\s+|\s+$/g, "");
                
                if (CodeSettings.indexOf("gutter") > -1)
                    oCodeSyntax.Gutter = true;

                if (CodeSettings.indexOf("toolbar") > -1)
                    oCodeSyntax.NoControls = true;

                if (CodeSettings.indexOf("collapse") > -1)
                    oCodeSyntax.Collapse = true;

                if (CodeSettings.indexOf("first-line") > -1) {

                    var match = /first-line: ([0-9]{1,4})/.exec(CodeSettings);
                    if (match != null && match.length > 0) {
                        oCodeSyntax.Firstline = match[1];
                    }
                    else {
                        oCodeSyntax.Firstline = 0;
                    }
                }
                
                // highlighted numbers
                if (CodeSettings.indexOf("highlight") > -1) {

                    // make sure we have a comma-seperated list
                    if (CodeSettings.match(/highlight: \[[0-9]+(,[0-9]+)*\]/)) {
                        // now grab the list
                        var match_hl = /highlight: \[(.*)\]/.exec(CodeSettings);
                        if (match_hl != null && match_hl.length > 0) {
                            oCodeSyntax.Highlight = match_hl[1];
                        }
                    }
                }


                if (CodeSettings.indexOf("ruler") > -1)
                    oCodeSyntax.Showcolumns = true;
            }
            else {
                oCodeSyntax.Lang = CodeSettings;
            }

        }

    } else {
        oContainerPre = null;
    }
}

// ----------------------
// config tabs
window.parent.AddTab('TabSourceCode', FCKLang.SyntaxHightlightTab1);
window.parent.AddTab('TabAdvanced', FCKLang.SyntaxHightlightTab2);

function OnDialogTabChange(tabCode) {
    ShowE('divSourceCode', (tabCode == 'TabSourceCode'));
    ShowE('divAdvanced', (tabCode == 'TabAdvanced'));
}
// ----------------------

window.onload = function() {

    // translate the dialog box texts
    oEditor.FCKLanguageManager.TranslatePage(document);
    // load current PRE block
    LoadSelected();
    // Show the "Ok" button.
    dialog.SetOkButton(true);
    // Select text field on load.
    SelectField('txtCode');

    // set version
    GetE('syntaxhighlight-version').innerHTML = version;

}

// ----------------------
// setup dialogue
function LoadSelected() {

    var ddLang = GetE('ddLang');

    if (!oCodeSyntax) {
        // creating new element
        if (FCKConfig.SyntaxHighlight2LangDefault != null) {

            for (count = 0; count < ddLang.length; count++) {

                if (ddLang.options[count].value == FCKConfig.SyntaxHighlight2LangDefault) {
                    ddLang.selectedIndex = count;
                    break;
                }
            }
        }

    }
    else {

        // editing existing element
        document.getElementById('txtCode').value = HTMLDecode(oCodeSyntax.Code);
        ddLang.value = oCodeSyntax.Lang;

        // set any advanced options
        if (oCodeSyntax.Advanced) {
            if (oCodeSyntax.Gutter)
                GetE('chkGutter').checked = true;

            if (oCodeSyntax.NoControls)
                GetE('chkNoControls').checked = true;

            if (oCodeSyntax.Collapse)
                GetE('chkCollapse').checked = true;

            if (oCodeSyntax.Firstline > 0) {
                GetE('chkLineCount').checked = true;
                GetE('txtLineCount').disabled = false;
                GetE('txtLineCount').value = oCodeSyntax.Firstline

            }

            if (oCodeSyntax.Highlight != null) {
                GetE('chkHighlightLine').checked = true;
                GetE('txtHighlightLines').disabled = false;
                GetE('txtHighlightLines').value = oCodeSyntax.Highlight

            }
            
            if (oCodeSyntax.Showcolumns)
                GetE('chkShowColumns').checked = true;

        }

    }
}

// ----------------------
// action on dialogue submit
function Ok() {
    var sCode = GetE('txtCode').value;
    var ddLang = GetE('ddLang').value + ";";
    var advanced = '';

    oEditor.FCKUndo.SaveUndoStep();

    if (!oContainerPre) {
        oContainerPre = FCK.CreateElement('PRE');
    }


    if (GetE('chkGutter').checked)
        advanced += "gutter: false; ";

    if (GetE('chkNoControls').checked)
        advanced += "toolbar: false; ";

    if (GetE('chkCollapse').checked)
        advanced += "collapse: true; ";

    // start line count from custom
    if (GetE('chkLineCount').checked)
        advanced += "first-line: " + GetE('txtLineCount').value + "; ";

    // highlight lines (strip all spaces)
    if (GetE('chkHighlightLine').checked)
        advanced += "highlight: [" + GetE('txtHighlightLines').value.replace(/\s/gi, "") + "]; ";

    // show ruler/column tool
    if (GetE('chkShowColumns').checked)
        advanced += "ruler: true; ";
   

    if (FCKBrowserInfo.IsIE) {
        // a bug in IE removes linebreaks in innerHTML, so lets use outerHTML instead
        oContainerPre.outerHTML = '<pre title="code" class="brush: ' + ddLang + advanced + '">' + HTMLEncode(sCode) + '</pre>';
    }
    else {
        oContainerPre.setAttribute("title", "code");
        oContainerPre.setAttribute("class", "brush: " + ddLang + advanced);
        oContainerPre.innerHTML = HTMLEncode(sCode);       
    }

    return true;
}

// ----------------------
// Helper functions
// ----------------------
function HTMLEncode(text) {
    if (!text)
        return '';

    text = text.replace(/&/g, '&amp;');
    text = text.replace(/</g, '&lt;');
    text = text.replace(/>/g, '&gt;');

    return text;
}

function HTMLDecode(text) {
    if (!text)
        return '';

    text = text.replace(/&gt;/g, '>');
    text = text.replace(/&lt;/g, '<');
    text = text.replace(/&amp;/g, '&');
    text = text.replace(/<br>/g, '\n');
    text = text.replace(/&quot;/g, '"');

    return text;
}

function changechk(checkbox, textfield) {

    if (checkbox.checked == true) {
        GetE(textfield).disabled = false;
    }
    else {
        GetE(textfield).disabled = true;
    }

}

