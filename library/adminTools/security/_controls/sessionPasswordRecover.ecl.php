'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Recuperar senha'
2=1
}
}
'content_ok'={
'pt'={
1='Se o usu�rio perder sua senha de acesso, o [text $system.caption] fornece uma ferramenta para a recupera��o da conta. Um link � enviado para o email do usu�rio atrav�s do qual ele poder� redefinir sua senha.

Este token ir� expirar em [$session_ttl] minutos e s� ser� v�lido uma �nica vez.

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
A ferramenta para a recupera��o de conta n�o est� dispon�vel.

[if($tips){]
Para utilizar esta ferramenta � necess�rio configurar uma conta para envio de emails atrav�s de SMTP.

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
