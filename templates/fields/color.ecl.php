'local'={
'filters'='color'
}
'text'={
'caption'={
'pt'={
1='Cor'
2=1
}
'en'={
1='Color'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[inline_lang]>[text][if$required{`*`;}]</label>

<span class="form-col-input-half input">
<a href="javascript:dialogColorOpen(''[$name]'', ''[$target]'')" class="button button-border-radius" id="[$name]_link" style="background-color:[$value or $default]" role="button" aria-label="[text]">
<img src="[shared:icons/blank.gif]" width="26px" height="15px">
</a>
<input type="text" size="15" id="[$name]" name="[$name]" value="[$value]">
</span>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
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
dialogColorWindow.close();
}

[/cut]
'
