'local'={
'type'='descriptive'
}
'text'={
'content'={
'pt'={
1='
[card:green]
=== Cliente de banco de dados ===

O [text $system.caption] está utilizando o [$client].

* <a href="[$url_edit]"> Configurar o banco de dados </a>
[/card]

[card:green]
=== Filtragem de dados ===

Antes de serem enviados ao banco de dados, os dados são filtrados de acordo com a coluna: inteiros, strings, hashes, dados empacotados e assim por diante. Esta verificação é feita na camada mais interna, de forma que a verificação não dependa do módulo que envia os dados.

Um mecanismo próprio garante que caracteres especiais entram e saiam do banco de dados com segurança, incluindo bytes nulos e barras invertidas.
[/card]
'
2=1
4=1
5=2
6=1
}
}
}
