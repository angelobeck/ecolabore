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
1='Erros do banco de dados são registrados em arquivo de log.

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
1='O [text $system.caption] está configurado para não registrar erros do banco de dados em arquivo de log. Esta configuração pode ser utilizada em desenvolvimento, mas nunca deve ser utilizada em produção porque dificulta o diagnóstico e rastreamento de problemas.

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
