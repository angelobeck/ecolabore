'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Verifica��o de email'
2=1
}
}
'content_ok'={
'pt'={
1='Quando o usu�rio deseja cadastrar um email em sua conta, o [text $system.caption] envia uma mensagem de verifica��o com um link atrav�s do qual o usu�rio pode confirmar o recebimento da mensagem.

[if($tips){]
* <a href="[$url_edit]">Configurar conta padr�o para envio de emails atrav�s de SMTP</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
'content_alert'={
'pt'={
1='
A verifica��o de email n�o est� dispon�vel porque o envio de emails atrav�s de SMTP est� desativado.

[if($tips){]
* <a href="[$url_edit]">Configurar conta padr�o para envio de emails atrav�s de SMTP</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
