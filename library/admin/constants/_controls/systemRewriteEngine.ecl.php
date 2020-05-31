'flags'={
'condition'='server_is_apache'
'type'='checkbox'
'filter'='admin_constants_flag'
'target'='SYSTEM_REWRITE_ENGINE'
'field_name'='SYSTEM_REWRITE_ENGINE'
'value_cast'='bool'
'help'=1
}
'text'={
'caption'={
'pt'={
1='Ativar endereços elegantes utilizando o módulo Rewrite Engine do Apache (Poderá causar o bloqueio de outros serviços nesta pasta)'
2=1
}
'en'={
1=' Enable elegant addresses using the Apache Rewrite Engine module (May cause blocking of other services in this folder)'
}
}
'title'={
'pt'={
1='Endereços elegantes'
2=1
}
'en'={
1='Elegant addresses'
}
}
'content'={
'pt'={
1='
O [text $system.caption] detectou que este servidor é Apache e pode tentar ativar o módulo RewriteEngine.

Uma vez que todas as requisições feitas ao [text $system.caption] são sempre direcionadas para "index.php", o módulo Rewrite Engine pode redirecionar todas as requisições desta pasta para o "index.php", mesmo que "index.php" não esteja presente na requisição do usuário.

Isto torna o sistema mais seguro e os endereços (URLs) mais amigáveis aos usuários. Porém, outros serviços que porventura possam estar disponíveis nesta pasta ou em subpastas poderão se tornar inacessíveis.

Caso você queira oferecer outros serviços junto ao [text $system.caption], não o coloque na pasta raiz do seu domínio.
'
2=1
4=1
6=1
}
'en'={
1='
[Text $ system.caption] has detected that this server is Apache, and you can try to activate the RewriteEngine module.

Since all requests made to [text $ system.caption] are always directed to "index.php", the Rewrite Engine module can redirect all requests from this folder to "index.php", even though "index.php" is not present in the user''s request.

This makes the system more secure and addresses (URLs) more friendly. However, other services that may be available in this folder or in subfolders may become inaccessible.

If you want to offer other services next to [text $ system.caption], do not put it in the root folder of your domain.
'
4=1
6=1
}
}
}
