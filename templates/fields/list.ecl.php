'local'={
'filters'='select'
}
'text'={
'caption'={
'pt'={
1='Lista de opções'
2=1
}
'en'={
1='Options list'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<select id="[$name]" name="[$name]" size="7" class="form-col-input input">
[list{ loop{]
<option value="[$value]"[if($active){ ` selected`; }]>[text]</option>
[}}]
</select>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
