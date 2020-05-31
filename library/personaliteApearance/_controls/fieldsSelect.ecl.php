'notes'='
colors = [
currentValue 0
currentDefault 1
currentFrom 2
currentClass 3
currentProperty 4
currentName 5 
]
'
'html'='
<tr><td>
<label for="[$name]" class="label-font-family label-font-weight label-font-size label-line-height"[inline_lang]>[text]</label>
</td><td[if(!$help){] colspan="2"[}]>
<select id="[$name]" name="[$name]" onchange="dialogStyleRefresh()" class="input input-text-color input-background-color input-border-radius input-font-family input-font-weight input-font-size input-line-height">
[list{loop{]
<option value="[$value]"[if($active){ ` selected`; }]>[text]</option>
[}}]
</select>
[script]
properties.[$target] = []"[$value]", "[$default]", "[$from]", "[$class]", "[$property]", "[$name]"];
[/script]
[if $help{ `</td><td>`; nl; help(`form`); }]
</td></tr>
'
