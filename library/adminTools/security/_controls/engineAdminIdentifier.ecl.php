'local'={
'type'='reportItem'
}
'text'={
'caption'={
'pt'={
1='Identificação do administrador'
2=1
}
}
'content_ok'={
'pt'={
1='O administrador do sistema não se identifica como "admin".

[if($tips){]
* <a href="[$url_edit]">Configurar identificação do administrador</a>
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
1='O administrador do sistema se conecta utilizando "admin" como identificador. Este é meio caminho andado para quem deseja descobrir a senha e obter acesso.

Recomendamos que altere o identificador do administrador para qualquer coisa diferente, preferencialmente que seja diferente do seu nome ou do nome do domínio.
[if($tips){]
* <a href="[$url_edit]">Configurar identificação do administrador</a>
[}]
'
2=1
4=1
5=2
6=1
}
}
}
