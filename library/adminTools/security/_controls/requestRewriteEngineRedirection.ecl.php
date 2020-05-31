'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Redirecionamento de requisições através de RewriteEngine'
2=1
}
}
'content_ok'={
'pt'={
1='
Todas as requisições são redirecionadas através de RewriteEngine para um único script, responsável por identificar e rotear a aplicação.

* Todas as páginas são entregues através deste script, com as devidas verificações de direitos de acesso.
* Todos os arquivos de usuários como imagens, áudios e outros arquivos para download só são disponíveis através deste script, que irá verificar as devidas permissões de acesso.
* Todos os complementos como scripts e folhas de estilos são entregues através deste script, com as devidas verificações de direitos de acesso.
* Todas as requisições a pontos de acesso para serviços são recebidas através deste script, com as devidas verificações de direitos de acesso.
* Qualquer requisição que tente acessar outros arquivos do servidor é redirecionada para um erro 404 "Não encontrado".

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
O mecanismo RewriteEngine não está presente ou não está corretamente configurado.

O [text $system.caption] pode funcionar normalmente, mas não há garantia de que os arquivos estejam protegidos no servidor. Outras medidas de proteção são indispensáveis para proteger os arquivos no servidor contra ataques e acessos indevidos.

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
