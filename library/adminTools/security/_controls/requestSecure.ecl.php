'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Aceitar somente conex�es seguras'
2=1
}
}
'content_ok'={
'pt'={
1='
O [text $system.caption] esta configurado para aceitar somente conex�es seguras atrav�s de HTTPS, e tentar� redirecionar conex�es HTTP para HTTPS.

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
1='Verifique se seu dom�nio possui certificados v�lidos, e quando for poss�vel se conectar atrav�s de HTTPS, configure o [text $system.caption] para aceitar somente conex�es seguras.

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
