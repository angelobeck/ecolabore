'text'={
'caption'={
'pt'={
1='Envio de arquivos'
2=1
}
'en'={
1='File sending'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<input type="file" id="[$name]" name="[$name][]]" class="form-col-input input" style="width:100%" multiple>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
'
