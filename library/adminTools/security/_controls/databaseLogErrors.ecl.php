'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Registrar erros do banco de dados'
2=1
}
}
'content_ok'={
'pt'={
1='Erros do banco de dados s�o registrados em arquivo de log.

[if($tips){]
* <a href="[$url_edit]">Configurar gerenciamento de erros do banco de dados</a>
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
1='O [text $system.caption] est� configurado para n�o registrar erros do banco de dados em arquivo de log. Esta configura��o pode ser utilizada em desenvolvimento, mas nunca deve ser utilizada em produ��o porque dificulta o diagn�stico e rastreamento de problemas.

[if($tips){]
* <a href="[$url_edit]">Configurar gerenciamento de erros do banco de dados</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
