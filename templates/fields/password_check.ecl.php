'text'={
'caption'={
'pt'={
1='Entrada de senha com verificação de força'
2=1
}
'en'={
1='Password input with password strength'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<input type="password" id="[$name]" name="[$name]" value="[$value]" class="form-col-input-half input" style="width:100%" onkeyup="passwordStrength(this.value, ''[$name]'')">
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>

<div class="form-control">
<label class="form-col-label" for="[$name]_repeat"[lang:field_user_repeat_password]>[text:field_user_repeat_password][if$required{`*`;}]</label>
<input type="password" id="[$name]_repeat" name="[$name]_repeat" value="[$value]" class="form-col-input-half input" style="width:100%">
</div>
<div class="form-control">
<input type="checkbox" id="[$name]_show_password" onchange="showPassword(''[$name]'')">
<label class="form-col-reverse-label" for="[$name]_show_password"[lang:field_user_show_password]>[text:field_user_show_password]</label>
</div>

<div class="form-control">
<div class="form-col-label">[text:label_user_password_strength]</div>
<div class="form-col-input">
<div id="[$name]_strength_text"></div>
<progress id="[$name]_strength_progress" value="0" max="100"></progress>
</div>
</div>
[cut:script password]

function showPassword(id){
var field1 = document.getElementById (id);
var field2 = document.getElementById (id + "_repeat");
var checkbox = document.getElementById (id + "_show_password");
 if (checkbox.checked){
field1.setAttribute("type", "text"); 
field2.setAttribute("type", "text"); 
}else{
field1.setAttribute ("type", "password");
field2.setAttribute("type", "password"); 
}
}

function passwordStrength (password, id){
var score = 0;

var upperExists = 0;
var lowerExists = 0;
var numberExists = 0;
var symbolExists = 0;

var lastGroup = 0;
var oldGroup = 0;

for (var i = 0; i < password.length; i++){
var c = "" + password.substr (i, 1);

if(/[]A-Z]/.test(c)){
upperExists = 12;
if(lastGroup == 1 && oldGroup == 1)
score += 1;
else if (lastGroup == 1)
score += 3;
else
score += 6;
oldGroup = lastGroup;
lastGroup = 1;
}

else if(/[]a-z@.]/.test(c)){
lowerExists = 12;
if(lastGroup == 2 && oldGroup == 2)
score += 1;
else if (lastGroup == 2)
score += 3;
else
score += 6;
oldGroup = lastGroup;
lastGroup = 2;
}

else if(/[]0-9]/.test(c)){
numberExists = 12;
if(lastGroup == 3 && oldGroup == 3)
score += 1;
else if (lastGroup == 3)
score += 3;
else
score += 6;
oldGroup = lastGroup;
lastGroup = 3;
}

else {
symbolExists = 12;
if(lastGroup == 4 && oldGroup == 4)
score += 2;
else if (lastGroup == 4)
score += 4;
else
score += 8;
oldGroup = lastGroup;
lastGroup = 4;
}

}

score += upperExists + lowerExists + numberExists + symbolExists;

if (score > 100)
score = 100;
var text = document.getElementById (id + "_strength_text");
var progress = document.getElementById (id + "_strength_progress");

text.innerHTML = score + "%";
progress.value = score;
};

function passwordGenerate(){

};
[/cut]
'
