'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Exibir erros de execução'
2=1
}
}
'content_ok'={
'pt'={
1='Erros de execução não são exibidos no navegador.

[if($tips){]
* <a href="[$url_edit]">Configurar gerenciamento de erros de execução</a>
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
1='O [text $system.caption] está configurado para imprimir erros de execução do PHP nas páginas enviadas ao navegador. Esta configuração pode ser utilizada em desenvolvimento, mas nunca deve ser utilizada em produção por poder expor informações sensíveis do sistema.

[if($tips){]
* <a href="[$url_edit]">Configurar gerenciamento de erros de execução</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
