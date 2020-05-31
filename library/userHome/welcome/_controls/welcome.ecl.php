'flags'={
'icon'='default'
}
'text'={
'caption'={
'pt'={
1='Bem-vind[gender:o|a]'
2=1
5=2
6=1
}
'en'={
1='Welcome'
2=1
}
}
'content'={
'pt'={
1='

Olá [text $user.caption],

[if($document.user_is_admin){]

Você está conectad[gender:o|a] como administrador[gender:|a].

[}else{]

Você está conectad[gender:o|a] ao [text $system.caption].

[}]

[if($last_login){]

A última vez que nos encontramos foi em [scope(`date`, $last_login){ text(`date_formated_full`); }].

[}]

[mod:user_quoteoftheday]
[mod:user_alerts]
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

[if($last_login){]

The last time we met was in [scope(`date`, $last_login){ text(`date_formated_full`); }].

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
