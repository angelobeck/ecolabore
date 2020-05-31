'local'={
'filters'='mail
numeric
text'
}
'text'={
'caption'={
'pt'={
1='Entrada de texto pequena'
2=1
}
'en'={
1='Small text input'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<input type="text" id="[$name]" name="[$name]" value="[$value]" class="form-col-input-half input" style="width:100%">
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
