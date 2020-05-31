'notes'='
properties = [
currentValue 0
currentDefault 1
currentFrom 2
currentClass 3
currentProperty 4
currentName 5 
]
'
'html'='
[cut:script select_font]

// opens select font dialog
dialogFontWindow = false;
dialogFontName = "";
dialogFontTarget = "";

function dialogFontOpen (name, target){
dialogFontTarget = target;
dialogFontName = name;
var left = (screen.width) ? (screen.width-250)/2 : 250;
var top = (screen.height) ? (screen.height-550)/2 : 25;
dialogFontWindow = window.open ("[$url]", "", "location=no, menubar=no, personalbar=no, resizable=no, scrollbars=no, status=no, toolbar=no, width=250, height=550, top="+top+", left="+left);
}

// transfer current font to dialog window
function dialogFontLoad(){
var values = {}
values.fontName = document.getElementById(dialogFontName + "_font_name").value;
values.fontWeight = document.getElementById(dialogFontName + "_font_weight").value;
values.fontSize = document.getElementById(dialogFontName + "_font_size").value;
values.lineHeight = document.getElementById(dialogFontName + "_line_height").value;
return values;
}

// closes select font dialog
function dialogFontClose (values){
document.getElementById(dialogFontName + "_font_name").value = values.fontName;
document.getElementById(dialogFontName + "_font_family").value = values.fontFamily;
document.getElementById(dialogFontName + "_font_weight").value = values.fontWeight;
document.getElementById(dialogFontName + "_font_size").value = values.fontSize;
document.getElementById(dialogFontName + "_line_height").value = values.lineHeight;

dialogFontWindow.close();
dialogStyleRefresh();
}
[/cut]
<tr><td>
<span class="label label-font-family label-font-weight label-font-size label-line-height">[text]</span>
</td><td colspan="2">
<a href="javascript:dialogFontOpen(''[$name]'', ''[$target]'')" class="button button-font-family button-font-weight button-font-size button-line-height button-border-radius">[text]
</a>
<input type="hidden" id="[$name]_font_name" name="[$name]_font_name" value="[$font-name-value]">
<input type="hidden" id="[$name]_font_family" name="[$name]_font_family" value="[$font-family-value]">
<input type="hidden" id="[$name]_font_weight" name="[$name]_font_weight" value="[$font-weight-value]">
<input type="hidden" id="[$name]_font_size" name="[$name]_font_size" value="[$font-size-value]">
<input type="hidden" id="[$name]_line_height" name="[$name]_line_height" value="[$line-height-value]">
[if($new-font-face){
cut(`style`); $font-face-value; nl; /cut;
}]
[script]
[if $font-family-enabled {]
properties.[$target]_font_name = []"[$font-name-value]", "[$font-name-default]", "[$font-name-from]", "", "", "[$name]_font_name"];
properties.[$target]_font_family = []"[$font-family-value]", "[$font-family-default]", "[$font-family-from]", "[$class]-font-family", "fontFamily", "[$name]_font_family"];
[}]
[if $font-weight-enabled {]
properties.[$target]_font_weight = []"[$font-weight-value]", "[$font-weight-default]", "[$font-weight-from]", "[$class]-font-weight", "fontWeight", "[$name]_font_weight"];
[}]
[if $font-size-enabled {]
properties.[$target]_font_size = []"[$font-size-value]", "[$font-size-default]", "[$font-size-from]", "[$class]-font-size", "fontSize", "[$name]_font_size"];
[}]
[if $line-height-enabled {]
properties.[$target]_line_height = []"[$line-height-value]", "[$line-height-default]", "[$line-height-from]", "[$class]-line-height", "lineHeight", "[$name]_line_height"];
[}]
[/script]
[if $help{ `</td><td>`; nl; help(`form`); }]
</td></tr>
'
