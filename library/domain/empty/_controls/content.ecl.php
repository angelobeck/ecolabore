'text'={
'caption'={
'pt'={
1='Início'
2=1
}
'en'={
1='Home'
2=1
}
}
'title'={
'pt'={
1='Aguardando a publicação de conteúdo'
2=1
}
'en'={
1='Waiting for publishing content'
2=1
}
}
'description'={
'pt'={
1=''
2=1
}
}
'content'={
'pt'={
1='

[if $document.user_is_admin]


[}elseif $document.user_is_connected]

Olá [text $user.caption],

Sua credencial não lhe dá direito a editar esta página.

[}else{]

Se você é um administrador deste domínio, conecte-se e comece a criar seu site agora mesmo!

[} // if]
'
2=1
4=1
6=1
}
'en'={
1='
[if $document.user_is_admin]

[}elseif $document.user_is_connected]

Hello [text $user.caption],

Your credential does not give you access to edit this page.

[}else{]

If you are an administrator of this domain, log in and start creating your website right now!

[} // if]
'
2=1
4=1
5=2
6=1
}
}
}
'flags'={
'personalite_disabled'=1
}
