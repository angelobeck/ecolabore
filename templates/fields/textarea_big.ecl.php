'local'={
'filters'='textarea'
}
'text'={
'caption'={
'pt'={
1='Área de texto multilinha grande'
2=1
}
'en'={
1='Big textarea'
}
}
}
'html'='<tr><td[if $help{] colspan="2"[}else{] colspan="3"[}]>
<label for="[$name]"[lang]>[text; if$required{`*`;}]</label>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</td></tr>
<tr><td colspan="3">
<textarea id="[$name]" name="[$name]" class="input" style="width:100%; height:20em" >[$value]</textarea>
</td></tr>
'
