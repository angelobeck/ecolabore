'flags'={
'modLayout_base'='mail'
}
'text'={
'caption'={
'pt'={
1='Verificar email'
2=1
}
'en'={
1='Email verification'
}
}
'title'={
'pt'={
1='Verifica��o de email para'
2=1
}
'en'={
1='Email verification to'
}
}
'content'={
'pt'={
1='
Ol� [text $user.caption],

Voc� est� recebendo esta mensagem porque uma solicita��o de verifica��o de email foi feita no [text $system.caption].

[if($user.security){]

Esta � a sua frase de seguran�a:

"[text $user.security]"

[}]

Para continuar, acione o hyperlink a seguir ou copie-o e cole-o na barra de endere�o do seu navegador:

<p><a href="[$document.url]">[$document.url]</a></p>

Caso n�o tenha feito esta solicita��o, apenas desconsidere esta mensagem.

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

You are receiving this message because a email verification request was made in [text $system.caption].

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
