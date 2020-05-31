'local'={
'filters'='address'
}
'text'={
'caption'={
'pt'={
1='Endereço com preenchimento automático por CEP no Brasil'
2=1
}
'en'={
1='Address with Brazil CEP auto fill'
}
}
}
'html'='<div class="form-control">
<label class="form-col-label" for="[$name]_postal_code"[lang]> [text:field_address_postal_code] </label>
<input type="text" id="[$name]_postal_code" name="[$name]_postal_code" value="[$address_postal_code]" class="form-col-input input" size="10" onchange="buscacep(''[$name]'', this.value)">
</div>
<div class="form-control">
<div class="form-col-input">
<label for="[$name]_street"[lang]> [text] - [text:field_address_street] </label>
<input type="text" id="[$name]_street" name="[$name]_street" value="[$address_street]" class="input" style="width:10em">
<label for="[$name]_number"[lang]> [text:field_address_number] </label>
<input type="text" id="[$name]_number" name="[$name]_number" value="[$address_number]" style="width:3em">
<label for="[$name]_complement"[lang]> [text:field_address_complement] </label>
<input type="text" id="[$name]_complement" name="[$name]_complement" value="[$address_complement]" class="input" style="width:5em">

<br>
<label for="[$name]_district"[lang]> [text:field_address_district] </label>
<input type="text" id="[$name]_district" name="[$name]_district" value="[$address_district]" class="input" style="width:75%">

<br>
<label for="[$name]_city"[lang]> [text:field_address_city] </label>
<input type="text" id="[$name]_city" name="[$name]_city" value="[$address_city]" class="input" style="width:75%">
<label for="[$name]_state"[lang]> [text:field_address_state] </label>
<input type="text" id="[$name]_state" name="[$name]_state" value="[$address_state]" class="input" style="width:2em">

<br>
<label for="[$name]_country"[lang]> [text:field_address_country] </label>
<input type="text" id="[$name]_country" name="[$name]_country" value="[$address_country]" class="input" style="width:75%">
</div>
</div>
[cut:script buscacep]
function buscacep (name, cep){
if (cep.length != 8)
return;

var request = new XMLHttpRequest();
request.onreadystatechange = function() {
if (this.readyState != 4 || this.status != 200) 
return;

var fields = JSON.parse(this.responseText);
document.getElementById ("[$name]_street").value = fields.logradouro;
document.getElementById ("[$name]_district").value = fields.bairro;
document.getElementById ("[$name]_city").value = fields.localidade;
document.getElementById ("[$name]_state").value = fields.uf;
document.getElementById ("[$name]_country").value = "Brasil";
}
request.open("GET", "https://viacep.com.br/ws/" + cep + "/json/", true);
request.send(); 

}
[/cut]
'
