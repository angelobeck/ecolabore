'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Exibir erros de execu��o'
2=1
}
}
'content_ok'={
'pt'={
1='Erros de execu��o n�o s�o exibidos no navegador.

[if($tips){]
* <a href="[$url_edit]">Configurar gerenciamento de erros de execu��o</a>
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
1='O [text $system.caption] est� configurado para imprimir erros de execu��o do PHP nas p�ginas enviadas ao navegador. Esta configura��o pode ser utilizada em desenvolvimento, mas nunca deve ser utilizada em produ��o por poder expor informa��es sens�veis do sistema.

[if($tips){]
* <a href="[$url_edit]">Configurar gerenciamento de erros de execu��o</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
