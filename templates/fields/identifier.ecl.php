'text'={
'caption'={
'pt'={
1='Entrada de identificador com geração de id'
2=1
}
'en'={
1='Identifier input with id generator'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
<input type="text" id="[$name]" name="[$name]" value="[text $nick]" class="form-col-input-half input" style="width:100%" onkeyup="idGenerator(this.value, ''[$name]'')">
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
</div>
<div class="form-control">
<label class="form-col-label" for="[$name]_identifier"[lang:field_user_identifier]>[text:field_user_identifier][if$required{`*`;}]</label>
<input type="text" id="[$name]_identifier" name="[$name]_identifier" value="[$identifier]" class="form-col-input-half input" style="width:100%">
</div>
[cut:script id_generator]

function idGenerator (name, id){
var identifier = document.getElementById (id + "_identifier");
identifier.value = basics.strings.toKeyword (name);
}

[/cut]
'
