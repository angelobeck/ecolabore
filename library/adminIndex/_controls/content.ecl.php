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
1='Administração do [text $system.caption]'
2=1
6=1
}
'en'={
1='[text $system.caption] Administration'
2=1
6=1
}
}
'content'={
'pt'={
1='
[if $document.user_is_admin{]
Olá [text $user.caption],

Bem-vind[gender:o|a] como administrador[gender:|a].

[}elseif $document.user_is_connected{]

Prezad[gender:o|a] [text $user.caption],

Sua credencial não lhe dá direito de acesso à estas ferramentas.

[}else{]

Se você é um administrador, conecte-se para obter acesso.

[} // if]
'
2=1
4=1
6=1
}
'en'={
1='[if $document.user_is_admin{]
Hello [text $user.caption],

Welcome as administrator.

[}elseif $document.user_is_connected{]

Dear [text $user.caption],

Your profile does not give you right of access to these tools.

[}else{]

If you are an administrator, connect to guive access.

[} // if]
'
2=1
4=1
5=2
6=1
}
}
}
