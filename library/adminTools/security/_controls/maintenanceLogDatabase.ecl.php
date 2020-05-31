'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Verificar registros no log do banco de dados'
2=1
}
}
'content_ok'={
'pt'={
1='
Não há registros no arquivo de log do banco de dados.

[if($tips){]
* <a href="[$url_edit]">Consultar arquivo de log do banco de dados</a>
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
1='
Falhas estão registradas no arquivo de log do banco de dados.

[if($tips){]
* <a href="[$url_edit]">Consultar arquivo de log do banco de dados</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
