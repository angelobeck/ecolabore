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
1='Ativar endere�os elegantes utilizando o m�dulo Rewrite Engine do Apache (Poder� causar o bloqueio de outros servi�os nesta pasta)'
2=1
}
'en'={
1=' Enable elegant addresses using the Apache Rewrite Engine module (May cause blocking of other services in this folder)'
}
}
'title'={
'pt'={
1='Endere�os elegantes'
2=1
}
'en'={
1='Elegant addresses'
}
}
'content'={
'pt'={
1='
O [text $system.caption] detectou que este servidor � Apache e pode tentar ativar o m�dulo RewriteEngine.

Uma vez que todas as requisi��es feitas ao [text $system.caption] s�o sempre direcionadas para "index.php", o m�dulo Rewrite Engine pode redirecionar todas as requisi��es desta pasta para o "index.php", mesmo que "index.php" n�o esteja presente na requisi��o do usu�rio.

Isto torna o sistema mais seguro e os endere�os (URLs) mais amig�veis aos usu�rios. Por�m, outros servi�os que porventura possam estar dispon�veis nesta pasta ou em subpastas poder�o se tornar inacess�veis.

Caso voc� queira oferecer outros servi�os junto ao [text $system.caption], n�o o coloque na pasta raiz do seu dom�nio.
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
