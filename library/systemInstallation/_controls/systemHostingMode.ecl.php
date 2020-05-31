'flags'={
'type'='radio'
'filter'='select'
'target'='SYSTEM_HOSTING_MODE'
'field_name'='SYSTEM_HOSTING_MODE'
'value_cast'='int'
'default_value'=0
'help'=1
}
'text'={
'caption'={
'pt'={
1='Modo de instalação'
2=1
}
'en'={
1='Installation mode'
}
}
'title'={
'pt'={
1='Modo de hospedagem'
2=1
}
'en'={
1='Hosting mode'
}
}
'content'={
'pt'={
1='
O [text $system.caption] é capaz de gerenciar múltiplos ambientes independentes entre si. Podem ser acessados como subpastas ou como subdomínios, de acordo com a configuração escolhida e das capacidades do servidor.

Para utilizar o modo "Portal", em que os múltiplos ambientes sejam acessados como subdomínios, certifique-se de:

* O servidor deve ser Apache e deve ter o módulo "rewriteEngine" habilitado
* O servidor deve estar configurado para aceitar qualquer subdomínio
* Todo o sistema deve estar na raiz do domínio
* O arquivo ".htdocs" correto deve ser colocado na raiz do seu sistema
* Você deve certificar-se de que outras aplicações não entrem em conflito com o [text $system.caption].

Em modo "ambientes como subpastas" você pode acessar os diversos ambientes como se fossem subpastas do seu domínio. Neste caso, todo o sistema poderá ser colocado dentro de uma subpasta do seu domínio, caso você queira evitar conflito com outras aplicações. Este modo permite acesso local através de "localhost".

O modo "Ambiente único" permite o acesso a um único ambiente, porém, sem restringir o acesso à área de configuração do sistema e aos perfis de usuários.
'
2=1
4=1
6=1
}
}
}
'children'={
#='~systemHostingModeSingle'
#='~systemHostingModeMultiple'
}
