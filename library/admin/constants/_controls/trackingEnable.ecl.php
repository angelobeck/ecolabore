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
1='Esta opção faz com que o [text $system.caption] colete dados do usuário.

Os seguintes dados serão registrados:

* Ip do usuário
* Data e hora da requisição
* Página alvo da requisição
* Link a partir de onde o usuário veio
* Agente do usuário (navegador e sistema operacional)
* Status do usuário (se o mesmo está conectado e possui direitos administrativos)

=== Observações ===

# Para economia de espaço, registros consecutivos de mesma procedência deixarão de lado dados repetidos ou inúteis.
# Páginas administrativas geradas pelo sistema não são registradas no log.
# Desativar o rastreamento não irá interferir na contagem de visitas das páginas.
# Você pode configurar para que outros dados sejam armazenados no log.
# Recomendamos que você cuide para que o arquivo de log não se torne muito grande. Você pode movê-lo para um local de backup e outro arquivo será gerado automaticamente. O nome do arquivo é ".tracking.db".
'
2=1
4=1
6=1
}
}
}
