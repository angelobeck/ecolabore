'flags'={
'type'='checkbox'
'filter'='admin_constants_flag'
'target'='SYSTEM_HTTPS_REDIRECT'
'field_name'='SYSTEM_HTTPS_REDIRECT'
'value_cast'='bool'
'help'=1
}
'text'={
'caption'={
'pt'={
1='Redirecionar conexões http para conexões seguras utilizando SSL via https'
2=1
}
'en'={
1='Redirect http connections to secure socket layer via https'
}
}
'title'={
'pt'={
1='Sempre utilizar conexões seguras'
2=1
}
'en'={
1='Aways use secure connections'
}
}
'content'={
'pt'={
1='
Se seu domínio possui uma assinatura digital, você pode configurar o [text $system.caption] para mudar de uma conexão aberta para uma conexão segura automaticamente.

Caso não possua uma assinatura digital e os usuários tentem conectar-se utilizando o protocolo https, os navegadores irão exibir mensagens desencorajadoras de falhas na autenticidade. Normalmente é possível utilizar criptografia em todos os servidores, porém, caso os usuários não saibam o que as mensagens significam, poderão acreditar que você está tentando enganá-los.
'
2=1
4=1
6=1
}
}
}
