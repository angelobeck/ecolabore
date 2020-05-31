'text'={
'caption'={
'pt'={
1='Inicialização do formulário'
2=1
}
'en'={
1='Formulary ending'
}
}
}
'html'='<form [if $name{]id="[$name]" name="[$name]" [}]action="[$url]" method="post" enctype="multipart/form-data" charset="[$document.charset]">
[if($roneypot){]
<input type="hidden" name="[$prefix]_command_time" id="[$prefix]_command_time" value="[$time]">
<input type="hidden" name="[$prefix]_command_password" id="[$prefix]_command_password" value="">
[cut:footerscript]
document.getElementById ("[$prefix]_command_password").value = "[$password]";
[/cut]
<label>[text:field_captcha_donotfill] <input type="text" name="[$prefix]_command_mail" id="[$prefix]_command_mail"></label>
[}]
<div class="form-layout-[$mod.form-layout or `grid`]">
'
