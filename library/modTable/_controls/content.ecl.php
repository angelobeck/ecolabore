'flags'={
'modLayout_base'='responsive'
'modLayout_from'='control'
'modLayout_name'='modTable_content'
}
'local'={
'scheme'='system'
}
'text'={
'title'={
'pt'={
1='Editar tabela'
2=1
}
'en'={
1='Edit table'
}
}
}
'html'='
[style]
#panel_left { position:absolute; width:15em; top:4rem; right:auto; bottom:4em; left:0.5em; vertical-align:middle; }
#panel_main { position:absolute; top:4rem; right:0.5em; bottom:4em; left:16em; overflow:auto; }
#panel_bottom {  position:absolute; top:auto; right:0.5em; bottom:0.5em; left:0.5em; height:3em; text-align:center; vertical-align:middle; }
#select_tab { width:100%; }
[/style]
[script]

// changes dialog tab
tabOld = 2;

function changeTab(){
var tabIndex = 2 + document.controls.select_tab.selectedIndex;
document.getElementById(''tab_content_''+tabOld).style.display = ''none'';
document.getElementById(''tab_content_''+tabIndex).style.display = ''block'';
tabOld = tabIndex;
}

// submit configurations
function dialogOk(){
document.controls.submit();
}

// Restore default configurations
function dialogRestore(){
document.controls.save.value = ''restore'';
document.controls.submit();
}

// closes dialog window
function dialogClose(){
window.close();
}

document.controls.select_tab.focus();
[/script]

<!-- panel_top -->
<div id="panel_top">
[mod:title{ list{]
<span class="caption">[text $title]</span>
[}}]
</div>
<!-- /panel_top -->

[mod:formulary{ list{]
<form id="controls" name="controls" action="[$url]" method="post" enctype="multipart/form-data" charset="[$document.charset]">
[next]

<!-- panel_left -->
<div id="panel_left">
<label for="select_tab">[text:field_tabs]</label><br >
<select id="select_tab" size="10" onchange="changeTab()">
[paste:tab_option]
</select>

</div>
<!-- /panel_left -->

<!-- panel_main -->
<div id="panel_main">

[cut:tab_option]
<option selected value="[$index]"> [text] </option>
[/cut]
<!-- tab_content_[$index] -->
<fieldset id="tab_content_[$index]" class="tab_content">
<legend>[text]</legend>
[script]

function tableClass ()
{ // tableClass

this.addCell = function (r, c)
{ // addCell
var tr = document.getElementById (''row_''+r);
var td = document.createElement (''td'');
var textarea = document.createElement (''textarea'');

textarea.setAttribute (''id'', ''[$name]_'' + r + ''_'' + c);
textarea.setAttribute (''name'', ''[$name]_'' + r + ''_'' + c);
td.appendChild (textarea);
tr.appendChild (td);
} // addCell

this.addCol = function ()
{ // addCol
var maxCols = document.getElementById (''[$name]_maxCols'');
var maxRows = document.getElementById (''[$name]_maxRows'');
var c = maxCols.value;
maxCols.value ++;

for (var r = 0; r < maxRows.value; r++)
{ // each row
this.addCell (r, c);
} // each row
} // addCol

this.addRow = function ()
{ // addRow
var maxCols = document.getElementById (''[$name]_maxCols'');
var maxRows = document.getElementById (''[$name]_maxRows'');
var r = maxRows.value;
maxRows.value ++;

var tr = document.createElement (''tr'');
tr.setAttribute (''id'', ''row_''+r);
var table = document.getElementById (''[$name]_table'');
table.appendChild (tr);

for (var c = 0; c < maxCols.value; c++)
{ // each row
this.addCell (r, c);
} // each row
} // addRow

} // tableClass

var table = new tableClass();

[/script]
<a href="javascript:table.addCol()">Adicionar coluna</a> &bull; 
<a href="javascript:table.addRow()">Adicionar linha</a>
<table id="[$name]_table">
[script]

// points to the html table
var tableName = ''[$name]'';
[/script]
[list{ loop{]
<tr id="row_[$row]">
[list{ loop{]
<td>
<textarea id="[$name]" name="[$name]">[$value]</textarea>
</td>
[}}]
</tr>
[}}]
</table>
</fieldset>
<!-- /tab_content_[$index] -->

[next]
[loop{
if(!$last){
cut:tab_option]
<option value="[$index]"> [text] </option>
[/cut]

<!-- tab_content_[$index] -->
<fieldset id="tab_content_[$index]" class="tab_content" style="display:none">
<legend>[text]</legend>
<table>
[list{ loop{ field; }}]
</table>
</fieldset>
<!-- /tab_content_[$index] -->
[}else{]
[list{ loop{ ]
<input type="hidden" id="[$name]" name="[$name]" value="[$value]" >
[}}
} // if
}}]

</div>
<!-- /panel_main -->

</form>
<!-- panel_bottom -->
<div id="panel_bottom">
<hr ><br >
<button name="ok" id="ok" onclick="dialogOk()">[text:action_ok]</button>
<button name="cancel" id="cancel" onclick="dialogClose()">[text:action_cancel]</button>
</div>
<!-- /panel_bottom -->


[}]
'
