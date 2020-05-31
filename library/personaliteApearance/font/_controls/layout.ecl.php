'flags'={
'modLayout_base'='responsive'
'modLayout_from'='control'
'modLayout_name'='personaliteApearance_font_layout'
}
'local'={
'scheme'='system'
}
'text'={
'title'={
'pt'={
1='Escolher fonte'
}
'en'={
1='Choose font'
}
}
}
'html'='
<table>
<tr>
<td>
<label>[text:field_font_family]<br>
<select id="select-font-family" size="6" onchange="previewRefresh()">
[mod(`list`){list{loop{
if($font-face){ style; $font-face; nl; /style; }]
<option value="[$name]" data-stack="[$font-stack]">[text]</option>
[}}}]
</select>
</label>
</td>

<td>
<label>[text:field_font_weight]<br>
<select id="select-font-weight" size="6" onchange="previewRefresh()">
<option value="normal">Normal</option>
<option value="bold">Bold</option>
</select>
</label>
</td>

<td>
<label>[text:field_size]<br>
<select id="select-font-size" size="6" onchange="previewRefresh()">
<option value="0.50.5em</option>
<option value="0.60.6em</option>
<option value="0.70.7em</option>
<option value="0.80.8em</option>
<option value="0.90.9em</option>
<option value="11em</option>
<option value="1.11.1em</option>
<option value="1.21.2em</option>
<option value="1.31.3em</option>
<option value="1.41.4em</option>
<option value="1.51.5em</option>
<option value="1.61.6em</option>
<option value="1.81.8em</option>
<option value="22em</option>
<option value="2.252.25em</option>
<option value="2.52.5em</option>
<option value="2.752.75em</option>
<option value="33em</option>
</select>
</label>
</td>

<td>
<label>[text:field_line_height]<br>
<select id="select-line-height" size="6" onchange="previewRefresh()">
<option value="0.5">0.5em</option>
<option value="0.6">0.6em</option>
<option value="0.70.7em</option>
<option value="0.80.8em</option>
<option value="0.90.9em</option>
<option value="11em</option>
<option value="1.11.1em</option>
<option value="1.21.2em</option>
<option value="1.31.3em</option>
<option value="1.41.4em</option>
<option value="1.51.5em</option>
<option value="1.61.6em</option>
<option value="1.81.8em</option>
<option value="22em</option>
<option value="2.252.25em</option>
<option value="2.52.5em</option>
<option value="2.752.75em</option>
<option value="33em</option>
</select>
</label>
</td>

</tr>
</table>

<div id="preview-box">
<div id="preview-text" contenteditable>
ABCDEFGHIJKLMNOPQRSTUVWXYZ<br>
abcdefghijklmnopqrstuvwxyz<br>
1234567890
</div>
</div>
<div class="center">
<button onclick="actionOk()">[text:action_ok]</button>
<button onclick="actionClear()">[text:action_clear]</button>
<button onclick="window.close()">[text:action_close]</button>
</div>
[style]
.preview-box { width:100%; overflow:hidden; height:6rem; }
[/style]
[script]

selectFontFamily = document.getElementById ("select-font-family");
selectFontWeight = document.getElementById ("select-font-weight");
selectFontSize = document.getElementById ("select-font-size");
selectLineHeight = document.getElementById ("select-line-height");

function loadValues(){
var values = window.opener.dialogFontLoad ();

if (values.fontName == "disabled")
selectFontFamily.disabled = true;
else{
selectFontFamily.selectedIndex = 0;
for(var i = 0; i < selectFontFamily.length; i++){
if (selectFontFamily.options[]i].value == values.fontName){
selectFontFamily.selectedIndex = i;
break;
}
}
}

if (values.fontWeight == "disabled")
selectFontWeight.disabled = true;
else{
selectFontWeight.selectedIndex = 0;
for(var i = 0; i < selectFontWeight.length; i++){
if (selectFontWeight.options[]i].value == values.fontWeight){
selectFontWeight.selectedIndex = i;
break;
}
}
}

if (values.fontSize == "disabled")
selectFontSize.disabled = true;
else{
selectFontSize.selectedIndex = 0;
for(var i = 0; i < selectFontSize.length; i++){
if (selectFontSize.options[]i].value == values.fontSize){
selectFontSize.selectedIndex = i;
break;
}
}
}

if (values.lineHeight == "disabled")
selectLineHeight.disabled = true;
else{
selectLineHeight.selectedIndex = 0;
for(var i = 0; i < selectLineHeight.length; i++){
if (selectLineHeight.options[]i].value == values.lineHeight){
selectLineHeight.selectedIndex = i;
break;
}
}
}
previewRefresh();
}

function previewRefresh(){

var preview = document.getElementById ("preview-text");

if (!selectFontFamily.disabled)
preview.style.fontFamily = selectFontFamily.options[]selectFontFamily.selectedIndex].dataset.stack;
if (!selectFontWeight.disabled)
preview.style.fontWeight = selectFontWeight.value;
if (!selectFontSize.disabled)
preview.style.fontSize = selectFontSize.value;
if (!selectLineHeight.disabled)
preview.style.lineHeight = selectLineHeight.value;
}

function actionOk(){
var values = {};

if (selectFontFamily.disabled)
{
values.fontName  ="disabled";
values.fontFamily = "";
}
else 
{
values.fontName = selectFontFamily.value;
values.fontFamily = selectFontFamily.options[]selectFontFamily.selectedIndex].dataset.stack;
}

if (selectFontWeight.disabled)
values.fontWeight = "disabled";
else
values.fontWeight = selectFontWeight.value;

if (selectFontSize.disabled)
values.fontSize = "disabled";
else
values.fontSize = selectFontSize.value;

if (selectLineHeight.disabled)
values.lineHeight = "disabled";
else
values.lineHeight = selectLineHeight.value;

window.opener.dialogFontClose (values);
}

loadValues();

[/script]

'
