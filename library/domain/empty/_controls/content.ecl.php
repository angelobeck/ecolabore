'text'={
'caption'={
'pt'={
1='In�cio'
2=1
}
'en'={
1='Home'
2=1
}
}
'title'={
'pt'={
1='Aguardando a publica��o de conte�do'
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

Ol� [text $user.caption],

Sua credencial n�o lhe d� direito a editar esta p�gina.

[}else{]

Se voc� � um administrador deste dom�nio, conecte-se e comece a criar seu site agora mesmo!

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
