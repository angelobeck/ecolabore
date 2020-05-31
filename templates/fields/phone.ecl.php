'local'={
'filters'='phone'
}
'text'={
'caption'={
'pt'={
1='Telefone'
2=1
}
'en'={
1='Phone'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]_country"[lang]> [text] <span class="sr-only">[text:field_phone_country]</span></label>
<div class="form-col-input">
<input type="text" id="[$name]_country" name="[$name]_country" value="[$phone_country]" class="input" style="width:4em">
<label for="[$name]_area" class="sr-only"[lang]> [text:field_phone_area] </label>
<input type="text" id="[$name]_area" name="[$name]_area" value="[$phone_area]" style="width:3em">
<label for="[$name]_number" class="sr-only"[lang]> [text:field_phone_number] </label>
<input type="text" id="[$name]_number" name="[$name]_number" value="[$phone_number]" class="input" style="width:10em">
</div>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
'
