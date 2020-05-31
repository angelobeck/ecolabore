'local'={
'filters'='textarea'
}
'text'={
'caption'={
'pt'={
1='Área de texto multilinha'
2=1
}
'en'={
1='Textarea'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<textarea id="[$name]" name="[$name]" class="form-col-input input" style="width:100%; height:10em">[$value]</textarea>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
