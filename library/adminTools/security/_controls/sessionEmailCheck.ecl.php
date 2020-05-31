'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Verificação de email'
2=1
}
}
'content_ok'={
'pt'={
1='Quando o usuário deseja cadastrar um email em sua conta, o [text $system.caption] envia uma mensagem de verificação com um link através do qual o usuário pode confirmar o recebimento da mensagem.

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
A verificação de email não está disponível porque o envio de emails através de SMTP está desativado.

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
}
