'text'={
'caption'={
'pt'={
1='Entrada de senha com exibir senha'
2=1
}
'en'={
1='Password input with view password'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<input type="password" id="[$name]" name="[$name]" value="[$value]" class="form-col-input-half input" style="width:100%">
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
<div class="form-control">
<input type="checkbox" id="[$name]_show_password" onchange=" var field = document.getElementById (''[$name]''); if (this.checked){ field.setAttribute(''type'', ''text''); }else{ field.setAttribute (''type'', ''password''); }">
<label class="form-col-reverse-label" for="[$name]_show_password"[lang:field_user_show_password]>[text:field_user_show_password]</label>
</div>
'
