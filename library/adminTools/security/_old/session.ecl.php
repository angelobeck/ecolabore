'local'={
'type'='descriptive'
}
'text'={
'content'={
'pt'={
1='
== Seção ==

[card:green]
=== Armazenamento seguro de senhas ===

Periodicamente o [text $system.caption] verifica se as senhas estão sendo armazenadas utilizando a melhor tecnologia disponível.
[/card]

[card:green]
=== Prevenção de ataque de força bruta ===

O [text $system.caption] dispõe de mecanismos internos para a prevenção de ataques por força bruta.
[/card]

[card:green]
=== Verificar segurança da senha ===

O [text $system.caption] informa ao usuário sobre o nível de segurança da senha escolhida.
[/card]

[card:green]
=== Erros de login ===

Uma tentativa malograda de conectar-se não informa se o identificador do usuário existe no sistema.
[/card]

[card:green]
=== Prevenção de ataques por recarga ===

A solicitação de login é feita através de HTTP Request, evitando que, após um usuário deixar o navegador, outro usuário tente recarregar as páginas anteriormente abertas, forçando novamente o envio dos dados de login.
[/card]

[card:green]
=== Alteração periódica de senha ===

Periodicamente o [text $system.caption] recomenda aos usuários que alterem suas senhas.
[/card]

[card:green]
=== Último acesso ===

Ao conectar-se, o usuário pode conferir a data e horário da última vez em que esteve conectado. Isso pode ajudá-lo a verificar se outra pessoa está utilizando suas credenciais.
[/card]

[card:green]
=== Tempo limite ===

A seção do usuário será encerrada após [$ttl] minutos de inatividade.

[if($tips){]

* <a href="[$url_edit]">Editar tempo da seção </a>
[}]
[/card]

[card:green]
=== Frase de segurança ===

Ao se cadastrar, o usuário deve informar uma frase de segurança. Assim, ao receber e-mails do [text $system.caption], será capaz de se certificar da origem da mensagem.
[/card]
[/card]
'
2=1
4=1
5=2
6=1
}
}
}
