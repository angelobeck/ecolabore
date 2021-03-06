'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Redirecionamento de requisi��es atrav�s de RewriteEngine'
2=1
}
}
'content_ok'={
'pt'={
1='
Todas as requisi��es s�o redirecionadas atrav�s de RewriteEngine para um �nico script, respons�vel por identificar e rotear a aplica��o.

* Todas as p�ginas s�o entregues atrav�s deste script, com as devidas verifica��es de direitos de acesso.
* Todos os arquivos de usu�rios como imagens, �udios e outros arquivos para download s� s�o dispon�veis atrav�s deste script, que ir� verificar as devidas permiss�es de acesso.
* Todos os complementos como scripts e folhas de estilos s�o entregues atrav�s deste script, com as devidas verifica��es de direitos de acesso.
* Todas as requisi��es a pontos de acesso para servi�os s�o recebidas atrav�s deste script, com as devidas verifica��es de direitos de acesso.
* Qualquer requisi��o que tente acessar outros arquivos do servidor � redirecionada para um erro 404 "N�o encontrado".

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
1='
O mecanismo RewriteEngine n�o est� presente ou n�o est� corretamente configurado.

O [text $system.caption] pode funcionar normalmente, mas n�o h� garantia de que os arquivos estejam protegidos no servidor. Outras medidas de prote��o s�o indispens�veis para proteger os arquivos no servidor contra ataques e acessos indevidos.

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
