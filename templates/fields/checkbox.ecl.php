'local'={
'filters'='checkbox'
}
'text'={
'caption'={
'pt'={
1='Caixa de verificação'
2=1
}
'en'={
1='Check box'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<input type="checkbox" id="[$name]" name="[$name]" [if($value){ ` checked `; }] class="form-col-reverse-input input">
<label class="form-col-reverse-label" for="[$name]"[lang]>[text]</label>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
