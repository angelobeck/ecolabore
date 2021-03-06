'flags'={
'type'='checkbox'
'filter'='admin_constants_flag'
'target'='TRACKING_ENABLE'
'field_name'='TRACKING_ENABLE'
'value_cast'='bool'
'help'=1
}
'text'={
'caption'={
'pt'={
1='Ativar rastreamento'
2=1
}
'en'={
1='Tracking enable'
}
}
'title'={
'pt'={
1='Ativar rastreamento'
2=1
}
'en'={
1='Tracking enable'
}
}
'content'={
'pt'={
1='Esta op��o faz com que o [text $system.caption] colete dados do usu�rio.

Os seguintes dados ser�o registrados:

* Ip do usu�rio
* Data e hora da requisi��o
* P�gina alvo da requisi��o
* Link a partir de onde o usu�rio veio
* Agente do usu�rio (navegador e sistema operacional)
* Status do usu�rio (se o mesmo est� conectado e possui direitos administrativos)

=== Observa��es ===

# Para economia de espa�o, registros consecutivos de mesma proced�ncia deixar�o de lado dados repetidos ou in�teis.
# P�ginas administrativas geradas pelo sistema n�o s�o registradas no log.
# Desativar o rastreamento n�o ir� interferir na contagem de visitas das p�ginas.
# Voc� pode configurar para que outros dados sejam armazenados no log.
# Recomendamos que voc� cuide para que o arquivo de log n�o se torne muito grande. Voc� pode mov�-lo para um local de backup e outro arquivo ser� gerado automaticamente. O nome do arquivo � ".tracking.db".
'
2=1
4=1
6=1
}
}
}
