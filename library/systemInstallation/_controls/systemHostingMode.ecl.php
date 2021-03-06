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
1='Modo de instala��o'
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
O [text $system.caption] � capaz de gerenciar m�ltiplos ambientes independentes entre si. Podem ser acessados como subpastas ou como subdom�nios, de acordo com a configura��o escolhida e das capacidades do servidor.

Para utilizar o modo "Portal", em que os m�ltiplos ambientes sejam acessados como subdom�nios, certifique-se de:

* O servidor deve ser Apache e deve ter o m�dulo "rewriteEngine" habilitado
* O servidor deve estar configurado para aceitar qualquer subdom�nio
* Todo o sistema deve estar na raiz do dom�nio
* O arquivo ".htdocs" correto deve ser colocado na raiz do seu sistema
* Voc� deve certificar-se de que outras aplica��es n�o entrem em conflito com o [text $system.caption].

Em modo "ambientes como subpastas" voc� pode acessar os diversos ambientes como se fossem subpastas do seu dom�nio. Neste caso, todo o sistema poder� ser colocado dentro de uma subpasta do seu dom�nio, caso voc� queira evitar conflito com outras aplica��es. Este modo permite acesso local atrav�s de "localhost".

O modo "Ambiente �nico" permite o acesso a um �nico ambiente, por�m, sem restringir o acesso � �rea de configura��o do sistema e aos perfis de usu�rios.
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
