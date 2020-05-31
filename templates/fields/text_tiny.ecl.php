'local'={
'filters'='numeric
text'
}
'text'={
'caption'={
'pt'={
1='Entrada de texto minúscula'
2=1
}
'en'={
1='Tiny text input'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<div class="form-col-input">
<input type="text" id="[$name]" name="[$name]" value="[$value]" class="input" style="width:5em">
</div>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
