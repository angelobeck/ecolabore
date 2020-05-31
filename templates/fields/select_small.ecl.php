'local'={
'filters'='select'
}
'text'={
'caption'={
'pt'={
1='Caixa de seleção pequena'
2=1
}
'en'={
1='Select box small'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<select id="[$name]" name="[$name]" class="form-col-input-half input" style="width:100%">
[list{ loop{]
<option value="[$value]"[if($active){ ` selected`; }]>[text]</option>
[}}]
</select>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
