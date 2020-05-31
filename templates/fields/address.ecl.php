'local'={
'filters'='address'
}
'text'={
'caption'={
'pt'={
1='Endereço'
2=1
}
'en'={
1='Address'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]_street"[lang]> [text] - [text:field_address_street] </label>
<div class="form-col-input">
<input type="text" id="[$name]_street" name="[$name]_street" value="[$address_street]" class="input" style="width:10em">
<label for="[$name]_number"[lang]> [text:field_address_number] </label>
<input type="text" id="[$name]_number" name="[$name]_number" value="[$address_number]" style="width:3em">
<label for="[$name]_complement"[lang]> [text:field_address_complement] </label>
<input type="text" id="[$name]_complement" name="[$name]_complement" value="[$address_complement]" class="input" style="width:5em">
</div>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
<div class="form-control">
<label for="[$name]_district"[lang]> [text:field_address_district] </label>
<input type="text" id="[$name]_district" name="[$name]_district" value="[$address_district]" class="input" style="width:75%">
</div>
<div class="form-control">
<label for="[$name]_city"[lang]> [text:field_address_city] </label>
<input type="text" id="[$name]_city" name="[$name]_city" value="[$address_city]" class="input" style="width:75%">
<label for="[$name]_state"[lang]> [text:field_address_state] </label>
<input type="text" id="[$name]_state" name="[$name]_state" value="[$address_state]" class="input" style="width:2em">
</div>
<div class="form-control">
<label for="[$name]_postal_code"[lang]> [text:field_address_postal_code] </label>
<input type="text" id="[$name]_postal_code" name="[$name]_postal_code" value="[$address_postal_code]" class="input" size="10">
</div>
<div class="form-control">
<label for="[$name]_country"[lang]> [text:field_address_country] </label>
<input type="text" id="[$name]_country" name="[$name]_country" value="[$address_country]" class="input" style="width:75%">
</div>
'
