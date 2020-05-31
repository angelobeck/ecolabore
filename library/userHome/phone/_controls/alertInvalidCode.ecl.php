'flags'={
'icon'='error'
}
'text'={
'caption'={
'pt'={
1='Código inválido'
2=1
5=2
6=1
}
'en'={
1='Invalid code'
2=1
}
}
'content'={
'pt'={
1='
O código informado não corresponde

'
2=1
4=1
5=2
6=1
}
'en'={
1='
Hello [text $user.caption],

[if($document.user_is_admin){]

You''re connected as administrator.

[}else{]

You''re connected in [text $system.caption].

[}]

[mod:user_quoteoftheday]
[mod:user_alerts]
'
2=1
4=1
5=2
6=1
}
}
}
