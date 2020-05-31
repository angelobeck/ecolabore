'flags'={
'type'='text_small'
'filter'='admin_constants_flag'
'target'='SYSTEM_SESSION_CACHE_EXPIRE'
'field_name'='SYSTEM_SESSION_CACHE_EXPIRE'
'value_cast'='int'
'help'=1
}
'text'={
'caption'={
'pt'={
1='Limpar cache de sessão após (minutos)'
2=1
}
'en'={
1='Clear session cache after (minutes)'
}
}
'title'={
'pt'={
1='Limpar cache de sessão'
2=1
}
'en'={
1='Clear session cache'
}
}
'content'={
'pt'={
1='
Mesmo após expirar, a sessão do usuário permanece em cache, permitindo que o usuário se reconnecte e continue algum trabalho pendente.

Quando o usuário submete um formulário após o período de validade da sessão, os dados recebidos são guardados na sessão. Caso o usuário se reconnecte imediatamente, será possível concluir a operação.

O cache será extinto após o período especificado neste campo.
'
2=1
4=1
}
}
}
