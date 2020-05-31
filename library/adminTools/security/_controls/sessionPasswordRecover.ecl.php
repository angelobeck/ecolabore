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
1='Se o usuário perder sua senha de acesso, o [text $system.caption] fornece uma ferramenta para a recuperação da conta. Um link é enviado para o email do usuário através do qual ele poderá redefinir sua senha.

Este token irá expirar em [$session_ttl] minutos e só será válido uma única vez.

[if($tips){]
* <a href="[$url_edit]">Configurar conta padrão para envio de emails através de SMTP</a>
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
A ferramenta para a recuperação de conta não está disponível.

[if($tips){]
Para utilizar esta ferramenta é necessário configurar uma conta para envio de emails através de SMTP.

* <a href="[$url_edit]">Configurar conta padrão para envio de emails através de SMTP</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
