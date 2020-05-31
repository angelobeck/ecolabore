'flags'={
'modLayout_base'='responsive'
'modLayout_from'='control'
'modLayout_name'='personaliteApearance_color_content'
'modLayout_cacheable'=43200
}
'local'={
'scheme'='system'
}
'text'={
'title'={
'pt'={
1='Escolher cor'
2=1
}
'en'={
1='Select color'
2=1
}
}
}
'html'='[style]
 .colors>div{
 	width: 25px; 
 	height: 25px;
 }

[/style]
[script]

// converts decimal to hexadecimal between 0 and 255
decHexValues = []''0'', ''1'', ''2'', ''3'', ''4'', ''5'', ''6'', ''7'', ''8'', ''9'', ''a'', ''b'', ''c'', ''d'', ''e'', ''f''];

function decHex(n){
nHi = parseInt(n / 16);
nLow = n - (nHi * 16);
nFound = decHexValues[]nHi] + decHexValues[]nLow];
return nFound;
}

// changes dialog mode (hex, dec, alpha)
dialogColorMode = 1;

function colorChangeMode(){
if (document.controls.mode[]0].checked){
document.controls.r.value = decHex(document.controls.r.value);
document.controls.g.value = decHex(document.controls.g.value);
document.controls.b.value = decHex(document.controls.b.value);
}else if (dialogColorMode == 0){
document.controls.r.value = parseInt(''0x''+document.controls.r.value);
document.controls.g.value = parseInt(''0x''+document.controls.g.value);
document.controls.b.value = parseInt(''0x''+document.controls.b.value);
}
 if (document.controls.mode[]2].checked){
document.getElementById(''a_hide'').style.display = ''block'';
}else if (dialogColorMode == 2){
document.getElementById(''a_hide'').style.display = ''none'';
}

if (document.controls.mode[]0].checked){
dialogColorMode = 0;
}else if (document.controls.mode[]2].checked){
dialogColorMode = 2;
}else{
dialogColorMode = 1;
}
dialogColorDisplay (document.controls.r.value, document.controls.g.value, document.controls.b.value);
}

// receive current color on start and determines mode (hex/dec/alpha)
function dialogColorStart(){
color = window.opener.dialogColorLoad();
r = 255;
g = 255;
b= 255;
if (!color){
}else if (color == "transparent"){
}else if (color.charAt(0) == ''#'' && color.length == 7){
document.controls.mode[]0].checked = true;
dialogColorMode = 0;
r = color.charAt(1)+color.charAt(2);
g = color.charAt(3)+color.charAt(4);
b = color.charAt(5)+color.charAt(6);
}else if (color.charAt(0) == ''#'' && color.length == 4){
document.controls.mode[]0].checked = true;
dialogColorMode = 0;
r = color.charAt(1)+color.charAt(1);
g = color.charAt(2)+color.charAt(2);
b = color.charAt(3)+color.charAt(3);
}else{
regExp = /^rgba?\(\s?(\d+)\,\s?(\d+)\,\s?(\d+)\,?\s?([]0-9.]*)/
found = regExp.exec(color);
if (found[]3]){
r = found[]1];
g = found[]2];
b = found[]3];
if (found[]4]){
a = found[]4];
a = 10 - parseInt(a * 10);
document.controls.a.selectedIndex = a;
document.controls.mode[]2].checked = true;
dialogColorMode = 2;
document.getElementById(''a_hide'').style.display = ''block'';
}
}
}
document.controls.r.value = r;
document.controls.g.value = g;
document.controls.b.value = b;
dialogColorDisplay (r, g, b);
}

// when user choose a color from palette
function paleta (r, g, b) {
if (!document.controls.mode[]0].checked){
r = parseInt(''0x''+r);
g = parseInt(''0x''+g);
b =parseInt(''0x''+b);
}
document.controls.r.value = r;
document.controls.g.value = g;
document.controls.b.value = b;
dialogColorDisplay(r, g, b);
}

// refresh color of the display
function dialogColorDisplay(r, g, b){
if (document.controls.mode[]0].checked){
color = ''#''+r+g+b;
}else if (document.controls.mode[]1].checked){
color = ''rgb(''+r+'', ''+g+'', ''+b+'')'';
}else{
color = ''rgba(''+r+'', ''+g+'', ''+b+'', ''+document.controls.a.value+'')'';
}
document.controls.color.value = color;
display_color = document.getElementById(''display_color'');
display_color.style.backgroundColor = color;
display_color_alt = document.getElementById(''display_color_alt'');
display_color_alt.alt = color;
}

// filter user entries and refresh display
dialogColorHex = /^[]0-9a-zA-Z]$/;
dialogColorHexDouble = /^[]0-9a-zA-Z]{2}$/;
function change_color() {
if (document.controls.mode[]0].checked){
document.controls.r.value = document.controls.r.value.toLowerCase();
document.controls.g.value = document.controls.g.value.toLowerCase();
document.controls.b.value = document.controls.b.value.toLowerCase();
if (dialogColorHex.exec(document.controls.r.value)){
r = document.controls.r.value+document.controls.r.value;
}else if (dialogColorHexDouble.exec(document.controls.r.value)){
r = document.controls.r.value;
}else{
r = ''00'';
document.controls.r.value = 0;
}
if (dialogColorHex.exec(document.controls.g.value)){
g = document.controls.g.value+document.controls.g.value;
}else if (dialogColorHexDouble.exec(document.controls.g.value)){
g = document.controls.g.value;
}else{
g = ''00'';
document.controls.g.value = 0;
}
if (dialogColorHex.exec(document.controls.b.value)){
b = document.controls.b.value+document.controls.b.value;
}else if (dialogColorHexDouble.exec(document.controls.b.value)){
b = document.controls.b.value;
}else{
b = ''00'';
document.controls.b.value = 0;
}
}else{
var r = parseInt(document.controls.r.value);
var g = parseInt(document.controls.g.value);
var b = parseInt(document.controls.b.value);
if (isNaN(r) || r < 0){r = 0;
}else if (r > 255){r = 255;
}
if (isNaN(g) || g < 0){g = 0;
}else if (g > 255){g = 255;
}
if (isNaN(b) || b < 0){b = 0;
}else if (b > 255){b = 255;
}
document.controls.r.value = r;
document.controls.g.value = g;
document.controls.b.value = b;
}

dialogColorDisplay(r, g, b);
}

// close width ok button
function dialogColorOk() {
window.opener.dialogColorClose(document.controls.color.value);
}

// Close with transparent color
function dialogColorTransparent() {
window.opener.dialogColorClose("transparent");
}

// cancel - closes dialog window
function dialogColorCancel() {
window.close ();
}
[/script]
<form name="controls" id="controls">
<input id="color" name="color" type="hidden" value="#FFFFFF">
<center>
<label><input type="radio" name="mode" id="mode" value="hex" onchange="colorChangeMode()" >[text:color_hex]</label>
<label><input type="radio" name="mode" id="mode" value="dec" checked onchange="colorChangeMode()">[text:color_dec]</label>
<label><input type="radio" name="mode" id="mode" value="alpha" onchange="colorChangeMode()">[text:color_alpha]</label>

<hr >

<table border="0" cellspacing="3" cellpadding="0" width="192" height="142">
<tr><td rowspan="4">
<div id="display_color" name="display_color" style="background-color:#FFFFFF; border-style:groove; height:100px; position:relative; width:100px;">
<div id="display_color_alt" name="display_color_alt" width="100" height="100" alt="#FFFFFF"></div>
</div>
</td><td>
<label for="r"> [text:color_red] </label>
</td><td>
<input type="text" id="r" name="r" value="" size="2" tabindex="1" onchange="change_color()">
</td></tr>

<tr><td>
<label for="g"> [text:color_green] </label>
</td><td>
<input type="text" id="g" name="g" value="" size="2" tabindex="2" onchange="change_color()">
</td></tr>

<tr><td>
<label for="b"> [text:color_blue] </label>
</td><td>
<input type="text" id="b" name="b" value="" size="2" tabindex="3" onchange="change_color()" >
</td></tr>
<tr><td>
<label for="a"> [text:color_alpha] </label>
</td><td>
<div id="a_hide" style="display:none;">
<select id="a" name="a" tabindex="4" onchange="change_color()">
<option value="1">100%</option>
<option value="0.9">90%</option>
<option value="0.8">80%</option>
<option value="0.7">70%</option>
<option value="0.6">60%</option>
<option value="0.5">50%</option>
<option value="0.4">40%</option>
<option value="0.3">30%</option>
<option value="0.2">20%</option>
<option value="0.1">10%</option>
<option value="0">0%</option>
</select>
</div>
</td></tr>
<tr><td colspan="3">
<hr >
<input type="button" id="ok" name="ok" value="[text:action_ok]" onclick="dialogColorOk()" tabindex="5" class="button" >
<input type="button" id="ok" name="ok" value="[text:color_transparent]" onclick="dialogColorTransparent()" tabindex="6" class="button" >
<input type="button" id="cancel" name="cancel" value="[text:action_cancel]" onclick="dialogColorCancel()" tabindex="7" class="button" >


</td></tr>
</table>


</form>
<table cellspacing="1" cellpadding="0" border="0">
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #000000;" alt="#000000" onClick="paleta(''00'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #333333;" alt="#333333" onClick="paleta(''33'', ''33'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #666666;" alt="#666666" onClick="paleta(''66'', ''66'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #999999;" alt="#999999" onClick="paleta(''99'', ''99'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCCCCC;" alt="#CCCCCC" onClick="paleta(''CC'', ''CC'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFFFF;" alt="#FFFFFF" onClick="paleta(''FF'', ''FF'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF0000;" alt="#FF0000" onClick="paleta(''FF'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFF00;" alt="#FFFF00" onClick="paleta(''FF'', ''FF'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FF00;" alt="#00FF00" onClick="paleta(''00'', ''FF'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FFFF;" alt="#00FFFF" onClick="paleta(''00'', ''FF'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #0000FF;" alt="#0000FF" onClick="paleta(''00'', ''00'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF00FF;" alt="#FF00FF" onClick="paleta(''FF'', ''00'', ''FF'')"></div></div></td>
</tr>

<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #000000;" alt="#000000" onClick="paleta(''00'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #003300;" alt="#003300" onClick="paleta(''00'', ''33'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #006600;" alt="#006600" onClick="paleta(''00'', ''66'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #009900;" alt="#009900" onClick="paleta(''00'', ''99'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00CC00;" alt="#00CC00" onClick="paleta(''00'', ''CC'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FF00;" alt="#00FF00" onClick="paleta(''00'', ''FF'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #330000;" alt="#330000" onClick="paleta(''33'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #333300;" alt="#333300" onClick="paleta(''33'', ''33'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #336600;" alt="#336600" onClick="paleta(''33'', ''66'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #339900;" alt="#339900" onClick="paleta(''33'', ''99'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33CC00;" alt="#33CC00" onClick="paleta(''33'', ''CC'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33FF00;" alt="#33FF00" onClick="paleta(''33'', ''FF'', ''00'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #000033;" alt="#000033" onClick="paleta(''00'', ''00'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #003333;" alt="#003333" onClick="paleta(''00'', ''33'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #006633;" alt="#006633" onClick="paleta(''00'', ''66'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #009933;" alt="#009933" onClick="paleta(''00'', ''99'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00CC33;" alt="#00CC33" onClick="paleta(''00'', ''CC'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FF33;" alt="#00FF33" onClick="paleta(''00'', ''FF'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #330033;" alt="#330033" onClick="paleta(''33'', ''00'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #333333;" alt="#333333" onClick="paleta(''33'', ''33'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #336633;" alt="#336633" onClick="paleta(''33'', ''66'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #339933;" alt="#339933" onClick="paleta(''33'', ''99'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33CC33;" alt="#33CC33" onClick="paleta(''33'', ''CC'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33FF33;" alt="#33FF33" onClick="paleta(''33'', ''FF'', ''33'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #000066;" alt="#000066" onClick="paleta(''00'', ''00'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #003366;" alt="#003366" onClick="paleta(''00'', ''33'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #006666;" alt="#006666" onClick="paleta(''00'', ''66'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #009966;" alt="#009966" onClick="paleta(''00'', ''99'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00CC66;" alt="#00CC66" onClick="paleta(''00'', ''CC'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FF66;" alt="#00FF66" onClick="paleta(''00'', ''FF'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #330066;" alt="#330066" onClick="paleta(''33'', ''00'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #333366;" alt="#333366" onClick="paleta(''33'', ''33'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #336666;" alt="#336666" onClick="paleta(''33'', ''66'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #339966;" alt="#339966" onClick="paleta(''33'', ''99'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33CC66;" alt="#33CC66" onClick="paleta(''33'', ''CC'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33FF66;" alt="#33FF66" onClick="paleta(''33'', ''FF'', ''66'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #000099;" alt="#000099" onClick="paleta(''00'', ''00'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #003399;" alt="#003399" onClick="paleta(''00'', ''33'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #006699;" alt="#006699" onClick="paleta(''00'', ''66'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #009999;" alt="#009999" onClick="paleta(''00'', ''99'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00CC99;" alt="#00CC99" onClick="paleta(''00'', ''CC'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FF99;" alt="#00FF99" onClick="paleta(''00'', ''FF'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #330099;" alt="#330099" onClick="paleta(''33'', ''00'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #333399;" alt="#333399" onClick="paleta(''33'', ''33'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #336699;" alt="#336699" onClick="paleta(''33'', ''66'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #339999;" alt="#339999" onClick="paleta(''33'', ''99'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33CC99;" alt="#33CC99" onClick="paleta(''33'', ''CC'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33FF99;" alt="#33FF99" onClick="paleta(''33'', ''FF'', ''99'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #0000CC;" alt="#0000CC" onClick="paleta(''00'', ''00'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #0033CC;" alt="#0033CC" onClick="paleta(''00'', ''33'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #0066CC;" alt="#0066CC" onClick="paleta(''00'', ''66'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #0099CC;" alt="#0099CC" onClick="paleta(''00'', ''99'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00CCCC;" alt="#00CCCC" onClick="paleta(''00'', ''CC'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FFCC;" alt="#00FFCC" onClick="paleta(''00'', ''FF'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3300CC;" alt="#3300CC" onClick="paleta(''33'', ''00'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3333CC;" alt="#3333CC" onClick="paleta(''33'', ''33'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3366CC;" alt="#3366CC" onClick="paleta(''33'', ''66'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3399CC;" alt="#3399CC" onClick="paleta(''33'', ''99'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33CCCC;" alt="#33CCCC" onClick="paleta(''33'', ''CC'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33FFCC;" alt="#33FFCC" onClick="paleta(''33'', ''FF'', ''CC'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #0000FF;" alt="#0000FF" onClick="paleta(''00'', ''00'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #0033FF;" alt="#0033FF" onClick="paleta(''00'', ''33'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #0066FF;" alt="#0066FF" onClick="paleta(''00'', ''66'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #0099FF;" alt="#0099FF" onClick="paleta(''00'', ''99'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00CCFF;" alt="#00CCFF" onClick="paleta(''00'', ''CC'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #00FFFF;" alt="#00FFFF" onClick="paleta(''00'', ''FF'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3300FF;" alt="#3300FF" onClick="paleta(''33'', ''00'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3333FF;" alt="#3333FF" onClick="paleta(''33'', ''33'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3366FF;" alt="#3366FF" onClick="paleta(''33'', ''66'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #3399FF;" alt="#3399FF" onClick="paleta(''33'', ''99'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33CCFF;" alt="#33CCFF" onClick="paleta(''33'', ''CC'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #33FFFF;" alt="#33FFFF" onClick="paleta(''33'', ''FF'', ''FF'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #660000;" alt="#660000" onClick="paleta(''66'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #663300;" alt="#663300" onClick="paleta(''66'', ''33'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #666600;" alt="#666600" onClick="paleta(''66'', ''66'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #669900;" alt="#669900" onClick="paleta(''66'', ''99'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66CC00;" alt="#66CC00" onClick="paleta(''66'', ''CC'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66FF00;" alt="#66FF00" onClick="paleta(''66'', ''FF'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #990000;" alt="#990000" onClick="paleta(''99'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #993300;" alt="#993300" onClick="paleta(''99'', ''33'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #996600;" alt="#996600" onClick="paleta(''99'', ''66'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #999900;" alt="#999900" onClick="paleta(''99'', ''99'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99CC00;" alt="#99CC00" onClick="paleta(''99'', ''CC'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99FF00;" alt="#99FF00" onClick="paleta(''99'', ''FF'', ''00'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #660033;" alt="#660033" onClick="paleta(''66'', ''00'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #663333;" alt="#663333" onClick="paleta(''66'', ''33'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #666633;" alt="#666633" onClick="paleta(''66'', ''66'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #669933;" alt="#669933" onClick="paleta(''66'', ''99'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66CC33;" alt="#66CC33" onClick="paleta(''66'', ''CC'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66FF33;" alt="#66FF33" onClick="paleta(''66'', ''FF'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #990033;" alt="#990033" onClick="paleta(''99'', ''00'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #993333;" alt="#993333" onClick="paleta(''99'', ''33'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #996633;" alt="#996633" onClick="paleta(''99'', ''66'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #999933;" alt="#999933" onClick="paleta(''99'', ''99'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99CC33;" alt="#99CC33" onClick="paleta(''99'', ''CC'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99FF33;" alt="#99FF33" onClick="paleta(''99'', ''FF'', ''33'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #660066;" alt="#660066" onClick="paleta(''66'', ''00'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #663366;" alt="#663366" onClick="paleta(''66'', ''33'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #666666;" alt="#666666" onClick="paleta(''66'', ''66'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #669966;" alt="#669966" onClick="paleta(''66'', ''99'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66CC66;" alt="#66CC66" onClick="paleta(''66'', ''CC'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66FF66;" alt="#66FF66" onClick="paleta(''66'', ''FF'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #990066;" alt="#990066" onClick="paleta(''99'', ''00'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #993366;" alt="#993366" onClick="paleta(''99'', ''33'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #996666;" alt="#996666" onClick="paleta(''99'', ''66'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #999966;" alt="#999966" onClick="paleta(''99'', ''99'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99CC66;" alt="#99CC66" onClick="paleta(''99'', ''CC'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99FF66;" alt="#99FF66" onClick="paleta(''99'', ''FF'', ''66'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #660099;" alt="#660099" onClick="paleta(''66'', ''00'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #663399;" alt="#663399" onClick="paleta(''66'', ''33'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #666699;" alt="#666699" onClick="paleta(''66'', ''66'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #669999;" alt="#669999" onClick="paleta(''66'', ''99'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66CC99;" alt="#66CC99" onClick="paleta(''66'', ''CC'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66FF99;" alt="#66FF99" onClick="paleta(''66'', ''FF'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #990099;" alt="#990099" onClick="paleta(''99'', ''00'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #993399;" alt="#993399" onClick="paleta(''99'', ''33'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #996699;" alt="#996699" onClick="paleta(''99'', ''66'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #999999;" alt="#999999" onClick="paleta(''99'', ''99'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99CC99;" alt="#99CC99" onClick="paleta(''99'', ''CC'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99FF99;" alt="#99FF99" onClick="paleta(''99'', ''FF'', ''99'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #6600CC;" alt="#6600CC" onClick="paleta(''66'', ''00'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #6633CC;" alt="#6633CC" onClick="paleta(''66'', ''33'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #6666CC;" alt="#6666CC" onClick="paleta(''66'', ''66'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #6699CC;" alt="#6699CC" onClick="paleta(''66'', ''99'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66CCCC;" alt="#66CCCC" onClick="paleta(''66'', ''CC'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66FFCC;" alt="#66FFCC" onClick="paleta(''66'', ''FF'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9900CC;" alt="#9900CC" onClick="paleta(''99'', ''00'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9933CC;" alt="#9933CC" onClick="paleta(''99'', ''33'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9966CC;" alt="#9966CC" onClick="paleta(''99'', ''66'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9999CC;" alt="#9999CC" onClick="paleta(''99'', ''99'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99CCCC;" alt="#99CCCC" onClick="paleta(''99'', ''CC'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99FFCC;" alt="#99FFCC" onClick="paleta(''99'', ''FF'', ''CC'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #6600FF;" alt="#6600FF" onClick="paleta(''66'', ''00'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #6633FF;" alt="#6633FF" onClick="paleta(''66'', ''33'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #6666FF;" alt="#6666FF" onClick="paleta(''66'', ''66'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #6699FF;" alt="#6699FF" onClick="paleta(''66'', ''99'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66CCFF;" alt="#66CCFF" onClick="paleta(''66'', ''CC'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #66FFFF;" alt="#66FFFF" onClick="paleta(''66'', ''FF'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9900FF;" alt="#9900FF" onClick="paleta(''99'', ''00'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9933FF;" alt="#9933FF" onClick="paleta(''99'', ''33'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9966FF;" alt="#9966FF" onClick="paleta(''99'', ''66'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #9999FF;" alt="#9999FF" onClick="paleta(''99'', ''99'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99CCFF;" alt="#99CCFF" onClick="paleta(''99'', ''CC'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #99FFFF;" alt="#99FFFF" onClick="paleta(''99'', ''FF'', ''FF'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #CC0000;" alt="#CC0000" onClick="paleta(''CC'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC3300;" alt="#CC3300" onClick="paleta(''CC'', ''33'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC6600;" alt="#CC6600" onClick="paleta(''CC'', ''66'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC9900;" alt="#CC9900" onClick="paleta(''CC'', ''99'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCCC00;" alt="#CCCC00" onClick="paleta(''CC'', ''CC'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCFF00;" alt="#CCFF00" onClick="paleta(''CC'', ''FF'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF0000;" alt="#FF0000" onClick="paleta(''FF'', ''00'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF3300;" alt="#FF3300" onClick="paleta(''FF'', ''33'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF6600;" alt="#FF6600" onClick="paleta(''FF'', ''66'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF9900;" alt="#FF9900" onClick="paleta(''FF'', ''99'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFCC00;" alt="#FFCC00" onClick="paleta(''FF'', ''CC'', ''00'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFF00;" alt="#FFFF00" onClick="paleta(''FF'', ''FF'', ''00'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #CC0033;" alt="#CC0033" onClick="paleta(''CC'', ''00'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC3333;" alt="#CC3333" onClick="paleta(''CC'', ''33'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC6633;" alt="#CC6633" onClick="paleta(''CC'', ''66'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC9933;" alt="#CC9933" onClick="paleta(''CC'', ''99'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCCC33;" alt="#CCCC33" onClick="paleta(''CC'', ''CC'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCFF33;" alt="#CCFF33" onClick="paleta(''CC'', ''FF'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF0033;" alt="#FF0033" onClick="paleta(''FF'', ''00'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF3333;" alt="#FF3333" onClick="paleta(''FF'', ''33'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF6633;" alt="#FF6633" onClick="paleta(''FF'', ''66'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF9933;" alt="#FF9933" onClick="paleta(''FF'', ''99'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFCC33;" alt="#FFCC33" onClick="paleta(''FF'', ''CC'', ''33'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFF33;" alt="#FFFF33" onClick="paleta(''FF'', ''FF'', ''33'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #CC0066;" alt="#CC0066" onClick="paleta(''CC'', ''00'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC3366;" alt="#CC3366" onClick="paleta(''CC'', ''33'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC6666;" alt="#CC6666" onClick="paleta(''CC'', ''66'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC9966;" alt="#CC9966" onClick="paleta(''CC'', ''99'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCCC66;" alt="#CCCC66" onClick="paleta(''CC'', ''CC'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCFF66;" alt="#CCFF66" onClick="paleta(''CC'', ''FF'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF0066;" alt="#FF0066" onClick="paleta(''FF'', ''00'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF3366;" alt="#FF3366" onClick="paleta(''FF'', ''33'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF6666;" alt="#FF6666" onClick="paleta(''FF'', ''66'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF9966;" alt="#FF9966" onClick="paleta(''FF'', ''99'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFCC66;" alt="#FFCC66" onClick="paleta(''FF'', ''CC'', ''66'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFF66;" alt="#FFFF66" onClick="paleta(''FF'', ''FF'', ''66'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #CC0099;" alt="#CC0099" onClick="paleta(''CC'', ''00'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC3399;" alt="#CC3399" onClick="paleta(''CC'', ''33'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC6699;" alt="#CC6699" onClick="paleta(''CC'', ''66'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC9999;" alt="#CC9999" onClick="paleta(''CC'', ''99'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCCC99;" alt="#CCCC99" onClick="paleta(''CC'', ''CC'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCFF99;" alt="#CCFF99" onClick="paleta(''CC'', ''FF'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF0099;" alt="#FF0099" onClick="paleta(''FF'', ''00'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF3399;" alt="#FF3399" onClick="paleta(''FF'', ''33'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF6699;" alt="#FF6699" onClick="paleta(''FF'', ''66'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF9999;" alt="#FF9999" onClick="paleta(''FF'', ''99'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFCC99;" alt="#FFCC99" onClick="paleta(''FF'', ''CC'', ''99'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFF99;" alt="#FFFF99" onClick="paleta(''FF'', ''FF'', ''99'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #CC00CC;" alt="#CC00CC" onClick="paleta(''CC'', ''00'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC33CC;" alt="#CC33CC" onClick="paleta(''CC'', ''33'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC66CC;" alt="#CC66CC" onClick="paleta(''CC'', ''66'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC99CC;" alt="#CC99CC" onClick="paleta(''CC'', ''99'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCCCCC;" alt="#CCCCCC" onClick="paleta(''CC'', ''CC'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCFFCC;" alt="#CCFFCC" onClick="paleta(''CC'', ''FF'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF00CC;" alt="#FF00CC" onClick="paleta(''FF'', ''00'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF33CC;" alt="#FF33CC" onClick="paleta(''FF'', ''33'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF66CC;" alt="#FF66CC" onClick="paleta(''FF'', ''66'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF99CC;" alt="#FF99CC" onClick="paleta(''FF'', ''99'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFCCCC;" alt="#FFCCCC" onClick="paleta(''FF'', ''CC'', ''CC'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFFCC;" alt="#FFFFCC" onClick="paleta(''FF'', ''FF'', ''CC'')"></div></div></td>
</tr>
<tr>
<td><div class="colors" ><div width="13" height="13" style="background: #CC00FF;" alt="#CC00FF" onClick="paleta(''CC'', ''00'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC33FF;" alt="#CC33FF" onClick="paleta(''CC'', ''33'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC66FF;" alt="#CC66FF" onClick="paleta(''CC'', ''66'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CC99FF;" alt="#CC99FF" onClick="paleta(''CC'', ''99'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCCCFF;" alt="#CCCCFF" onClick="paleta(''CC'', ''CC'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #CCFFFF;" alt="#CCFFFF" onClick="paleta(''CC'', ''FF'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF00FF;" alt="#FF00FF" onClick="paleta(''FF'', ''00'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF33FF;" alt="#FF33FF" onClick="paleta(''FF'', ''33'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF66FF;" alt="#FF66FF" onClick="paleta(''FF'', ''66'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FF99FF;" alt="#FF99FF" onClick="paleta(''FF'', ''99'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFCCFF;" alt="#FFCCFF" onClick="paleta(''FF'', ''CC'', ''FF'')"></div></div></td>
<td><div class="colors" ><div width="13" height="13" style="background: #FFFFFF;" alt="#FFFFFF" onClick="paleta(''FF'', ''FF'', ''FF'')"></div></div></td>
</tr>
</table>
</center>
[script]
document.onload = dialogColorStart();

document.controls.r.focus();
[/script]
'
'blocks'={
'labels/color/green'={
'text'={
'caption'={
'pt'={
1='Verde'
2=1
}
'en'={
1='Green'
2=1
}
}
}
}
'labels/color/red'={
'text'={
'caption'={
'pt'={
1='Vermelho'
2=1
}
'en'={
1='Red'
2=1
}
}
}
}
'labels/color/blue'={
'text'={
'caption'={
'pt'={
1='Azul'
2=1
}
'en'={
1='Blue'
2=1
}
}
}
}
'labels/color/hex'={
'text'={
'caption'={
'pt'={
1='Hexadecimal'
2=1
}
'en'={
1='Hexadecimal'
2=1
}
}
}
}
'labels/color/dec'={
'text'={
'caption'={
'pt'={
1='Decimal'
2=1
}
'en'={
1='Decimal'
2=1
}
}
}
}
'labels/color/alpha'={
'text'={
'caption'={
'pt'={
1='Alpha'
2=1
}
'en'={
1='Alpha'
2=1
}
}
}
}
'labels/color/transparent'={
'text'={
'caption'={
'pt'={
1='Transparente'
2=1
}
'en'={
1='Transparent'
2=1
}
}
}
}
}
