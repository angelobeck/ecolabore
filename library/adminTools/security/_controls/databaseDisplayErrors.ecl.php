'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Exibir erros do banco de dados'
2=1
}
}
'content_ok'={
'pt'={
1='Erros do banco de dados não são exibidos no navegador.

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
1='O [text $system.caption] está configurado para imprimir erros do banco de dados nas páginas enviadas ao navegador. Esta configuração pode ser utilizada em desenvolvimento, mas nunca deve ser utilizada em produção por poder expor informações sensíveis do sistema.

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
