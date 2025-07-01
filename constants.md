
## O processo de carregamento

O processo de carregamento da ecolabore engine envolve 3 arquivos principais:

1. O arquivo "index.php" da aplicação a ser chamada. Neste arquivo são feitas configurações de servidor como nome de domínio, exibição de erros, etc. Neste arquivo também  são informadas as pastas de todos os módulos necessários. Este arquivo pode ser chamado de "loader" e deverá ser editado para corresponder ao servidor onde o sistema está instalado.
2. O arquivo de configuração da aplicação, normalmente "config.php" que personaliza a aplicação com informações sobre o administrador, criptografia, banco de dados, estrutura de pastas, etc. Aplicações simples poderão não necessitar deste arquivo. Se você pretende rodar o mesmo sistema em sua máquina local e também rodar o mesmo sistema em produção, este arquivo deverá ser o mesmo nas duas instalações.
3. A engine. Quando o sistema está "empacotado", scripts, dados estáticos e templates HTML são embarcados em um único arquivo. Para o desenvolvimento estes arquivos ficam distribuídos em diversas pastas e serão incluídos sob demanda.

Arquivos JS e CSS também serão "empacotados" junto com a engine, gerando um único arquivo .js e um único arquivo .css.

## Constantes para configuração do servidor

* SERVER_HOSTING_MODE
* SERVER_PROTOCOL
* SERVER_HOST
* SERVER_REWRITE_ENGINE
* SERVER_SCRIPT_NAME
* SERVER_CONFIG_FILE
* SERVER_DISPLAY_ERRORS
* SERVER_LOG_ERRORS
* SERVER_LOG_FILE
* SERVER_DATABASE_LOG_FILE
* SERVER_TIME_LIMIT

* PATH_ROOT
* PATH_APPLICATION
* PATH_ENGINE

* MODULES

## Personalização da aplicação

* ADMIN_NAME
* ADMIN_GENDER
* ADMIN_PASSWORD
* ADMIN_HELPERS

* APPLICATION_ADMIN_NAME
* APPLICATION_USERS_NAME

* DATABASE_ENABLED
* DATABASE_CLIENT
* DATABASE_HOST
* DATABASE_USER
* DATABASE_PASSWORD
* DATABASE_DB
* DATABASE_PREFIX

* DEFAULT_CHARSET
* DEFAULT_DOMAIN_NAME
* DEFAULT_LANGUAGE
* DEFAULT_TIMEZONE
* DEFAULT_SESSION_TTL

* ENCRYPTION_ENABLED
* ENCRYPTION_CIPHER
* ENCRYPTION_KEY
* ENCRYPTION_HASH

* FOLDER_ACETS
* FOLDER_CACHE
* FOLDER_DATABASE
* FOLDER_SITES
* FOLDER_USERS

## Constantes definidas dentro da engine e que não devem ser editadas

* PACK_ENABLED
* PACK_FILE
* PACK_NAME
* PACK_TIME

* PATH_ACETS
* PATH_CACHE
* PATH_DATABASE
* PATH_SITES
* PATH_USERS

* CRLF
* TIME
