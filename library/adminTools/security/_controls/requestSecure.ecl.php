'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Aceitar somente conexões seguras'
2=1
}
}
'content_ok'={
'pt'={
1='
O [text $system.caption] esta configurado para aceitar somente conexões seguras através de HTTPS, e tentará redirecionar conexões HTTP para HTTPS.

[if($tips){]
* <a href="[$url_edit]">Configurar servidor</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
'content_fail'={
'pt'={
1='Verifique se seu domínio possui certificados válidos, e quando for possível se conectar através de HTTPS, configure o [text $system.caption] para aceitar somente conexões seguras.

[if($tips){]
* <a href="[$url_edit]">Configurar servidor</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
