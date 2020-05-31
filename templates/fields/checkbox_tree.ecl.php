'text'={
'caption'={
'pt'={
1='Caixas de verificação em árvore'
2=1
}
'en'={
1='Check boxes in tree'
}
}
}
'html'='[cut:script checkbox_tree]
function changeCheckboxVisibility (name)
{
name += "_sublevel";
var control = document.getElementById(name);
control.hidden = !control.hidden;
} // change checkbox visibility

function changeCheckboxTree (name)
{ // change checkboxTree
var checked = document.getElementById (name).checked;
alert (checked);
var checkboxList = checkboxTree[]name];
alert (checkboxList.length);
for(var i= 0; i < checkboxList.length; i++)
{ // loop checkboxes
var chName = checkboxList[]i];
var checkbox = document.getElementById (chName);
checkbox.checked = checked;
} // loop checkboxes
} // change checkboxTree


[/cut]
<div class="form-control[if($help){ ` form-control-help`; }]">
<input type="checkbox" id="[$name]" name="[$name]" [if($value){ ` checked `; }] class="form-col-reverse-input input" onchange="changeCheckboxTree(''[$name]'')">
<label class="form-col-reverse-label" for="[$name]"[lang]>
<a href="javascript:changeCheckboxVisibility(''[$name]'')">
[text]
</a>
</label>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
<div id="[$name]_sublevel" hidden>
[script]
checkboxTree.[$name] = []
[/script]
[list{loop{]
[script]
"[$name]"[if(!$last){ `,`; }]
[/script]
<div class="form-control">
<span class="form-col-reverse-input"></span>
<span class="form-col-reverse-label">
<input type="checkbox" id="[$name]" name="[$name]" [if($value){ ` checked `; }] class="input">
<label for="[$name]"[lang]>[text]</label>
</span>
</div>
[}}]
[script]
];
[/script]
</div>
'
