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
[cut:script select_color]

// opens select color dialog
dialogColorName = "";
dialogColorTarget = "";

function dialogColorOpen (name, target){
dialogColorTarget = target;
dialogColorName = name;
var left = (screen.width) ? (screen.width-250)/2 : 250;
var top = (screen.height) ? (screen.height-550)/2 : 25;
dialogColorWindow = window.open (''[$url]'', '''', ''location=no, menubar=no, personalbar=no, status=no, toolbar=no, width=250, height=550, top=''+top+'', left=''+left);
}

// transfer current color to dialog window
function dialogColorLoad(){
var value = document.getElementById(dialogColorName).value;
if (value == "")
value = document.getElementById(dialogColorName).dataset.current;

return value;
}

// closes select color dialog
function dialogColorClose(color){
document.getElementById(dialogColorName).value = color;
dialogStyleRefresh();
dialogColorWindow.close();
}

[/cut]
<tr><td>
<label for="[$name]" class="label-font-family label-font-weight label-font-size label-line-height"[inline_lang]>[text]</label>
</td><td[if(!$help){] colspan="2"[}]>
<a href="javascript:dialogColorOpen(''[$name]'', ''[$target]'')" class="button button-border-radius" id="[$name]_link" style="background-color:[$value or $default]" role="button" aria-label="[text]">
<img src="[shared:icons/blank.gif]" width="26px" height="15px">
</a>
<input type="text" size="15" id="[$name]" name="[$name]" value="[$value]" onchange="dialogStyleRefresh()" class="input input-text-color input-background-color input-border-radius input-font-family input-font-weight input-font-size input-line-height">
[script]
properties.[$target] = []"[$value]", "[$default]", "[$from]", "[$class]", "[$property]", "[$name]"];
[/script]
[if $help{ `</td><td>`; nl; help(`form`); }]
</td></tr>
'
