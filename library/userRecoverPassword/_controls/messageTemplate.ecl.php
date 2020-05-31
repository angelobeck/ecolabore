'flags'={
'modLayout_base'='mail'
}
'text'={
'caption'={
'pt'={
1='Recuperar senha'
2=1
}
'en'={
1='Recover password'
}
}
'title'={
'pt'={
1='Recuperar senha de'
2=1
}
'en'={
1='Recover password for'
}
}
'content'={
'pt'={
1='
Olá [text $user.caption],

Você está recebendo esta mensagem pois uma solicitação de recuperação de senha foi feita no [text $system.caption].

[if($user.security){]

Esta é a sua frase de segurança:

"[text $user.security]"

[}]

Para continuar, acione o hyperlink a seguir ou copie-o e cole-o na barra de endereço do seu navegador:

<p><a href="[$document.url]">[$document.url]</a></p>

Caso não tenha feito esta solicitação, apenas desconsidere esta mensagem.

A equipe do [text $system.caption].
'
2=1
4=1
5=2
6=1
}
'en'={
1='
 Hello [text $user.caption],

You are receiving this message because a password recovery request was made in [text $system.caption].

[if ($user.security) {]

This is your safety phrase:

"[text $user.security]"

[}]

To continue, click on the following hyperlink or copy and paste it into your browser''s address bar:

<p><a href="[$document.url]">[$document.url]</a></p>

If you have not made this request, just disregard this message.

The [text $system.caption] team.
'
2=1
4=1
5=2
6=1
}
}
}
