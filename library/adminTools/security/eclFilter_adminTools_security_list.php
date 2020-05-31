<?php

class eclFilter_adminTools_security_list
{ // class eclFilter_adminTools_security_list

static function create ($fieldName, $control, $formulary)
{ // function create
global $io, $store, $system;
$document = $formulary->document;
$domain = $system->child (SYSTEM_DEFAULT_DOMAIN_NAME);
$tips = $document->access (4);
$admin = $tips;

// == info ==
$formulary->appendChild ('adminTools_security_info');

// BestPractices
$formulary->appendChild ('adminTools_security_infoBestPractices');

// ContactInfo
if(!$domain)
$formulary->appendChild('adminTools_security_infoContactInfo', ['alert' => 1]);
elseif (!$domain->data['local'])
$formulary->appendChild('adminTools_security_infoContactInfo', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_DEFAULT_DOMAIN_NAME, '-tools', 'config', 'about']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_infoContactInfo', [
'ok' => 1,
'url' => $document->url ([SYSTEM_DEFAULT_DOMAIN_NAME, '-info']),
'url_edit' => $document->url ([SYSTEM_DEFAULT_DOMAIN_NAME, '-tools', 'config', 'about']),
'tips' => $tips
]);

// ServiceTerms
if(!$domain)
$local = [
'alert' => 1,
];
elseif ($id = $store->domainContent->findMarker ($domain->domainId, 5))
{ // ok
$pathway = $store->domainContent->pathway ($domain->domainId, $id);
$local = [
'ok' => 1,
'url' => $document->url ($pathway),
'url_edit' => $document->url ($pathway, true, '_section-edit'),
'tips' => $tips
];
} // ok
else
$local = [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_DEFAULT_DOMAIN_NAME, '-new-section'], true, '_create-terms'),
'tips' => $tips
];
$formulary->appendChild('adminTools_security_infoServiceTerms', $local);

// TermsAgree
$formulary->appendChild('adminTools_security_infoTermsAgree', $local);

// [todo] TermsUpdate
// $formulary->appendChild('adminTools_security_infoTermsUpdate', $local);

// PrivacyPolicy
if(!$domain)
$formulary->appendChild('adminTools_security_infoPrivacyPolicy', ['alert' => 1]);
elseif ($id = $store->domainContent->findMarker ($domain->domainId, 7))
{ // ok
$pathway = $store->domainContent->pathway ($domain->domainId, $id);
$formulary->appendChild('adminTools_security_infoPrivacyPolicy', [
'ok' => 1,
'url' => $document->url ($pathway),
'url_edit' => $document->url ($pathway, true, '_section-edit'),
'tips' => $tips
]);
} // ok
else
$formulary->appendChild('adminTools_security_infoPrivacyPolicy', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_DEFAULT_DOMAIN_NAME, '-new-section'], true, '_create-privacypolicy'),
'tips' => $tips
]);

// UserAbuse report
if (SYSTEM_ENABLE_USER_SUBSCRIPTIONS)
$formulary->appendChild('adminTools_security_infoUserAbuseReport', [
'ok' => 1,
'enable' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'hosting']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_infoUserAbuseReport', [
'ok' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'hosting']),
'tips' => $tips
]);

// DomainAbuse report
if (SYSTEM_ENABLE_USER_DOMAINS)
$formulary->appendChild('adminTools_security_infoDomainAbuseReport', [
'ok' => 1,
'enable' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'hosting']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_infoDomainAbuseReport', [
'ok' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'hosting']),
'tips' => $tips
]);

// Robots.txt
if($document->rewriteEngine)
$local = ['ok' => 1];
else
$local = ['alert' => 1];
$formulary->appendChild('adminTools_security_infoRobots', $local);

// Sitemap.xml
$formulary->appendChild('adminTools_security_infoSitemap', $local);

// HtmlRobots
$formulary->appendChild('adminTools_security_infoHtmlRobots', ['ok' => 1]);

// == Seção ==
$formulary->appendChild('adminTools_security_session');

// [todo] Dispôr de mecanismos para evitar ataques por força bruta.
// [todo] Bloquear usuário após um número de tentativas malogradas de login.
// [todo] Não permitir que o usuário desbloqueie a conta bloqueada - por exemplo removendo os cookies.

// Informar ao usuário sobre a força da senha escolhida, Exigir senhas com pelo menos 6 caracteres, contendo letras maiúsculas, minúsculas e números. Caracteres especiais também devem ser aceitos e o comprimento da senha não deve ser limitado.
$formulary->appendChild('adminTools_security_sessionPasswordStrengthCheck');

// Caso haja falha no login, não deixar transparecer se o usuário existe ou não cadastrado no sistema.
$formulary->appendChild('adminTools_security_sessionLoginFailureReport');

// [todo] Prevenir ataques por recarga, evitando que o navegador reenvie os dados de login.

// Lembrar o usuário de alterar sua senha periodicamente (a cada 6 meses).
$formulary->appendChild('adminTools_security_sessionPasswordUpdate');

// Informar ao usuário a data do seu último acesso, permitindo conferir se foi ele mesmo que se conectou.
$formulary->appendChild('adminTools_security_sessionLoginLastAccess');

// Expirar a sessão do usuário após um tempo limite de inatividade (30 minutos).
$formulary->appendChild('adminTools_security_sessionExpires', [
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'setup']),
'session_ttl' => SYSTEM_SESSION_TTL / 60,
'tips' => $tips
]);

// [todo] Expirar a sessão do usuário após um tempo absoluto de atividade (12 horas).

// Disponibilizar link para desconectar.
$formulary->appendChild('adminTools_security_sessionLogout');

// Disponibilizar link para redefinir senha.
$formulary->appendChild('adminTools_security_sessionPasswordChange');

// Disponibilizar link para descadastrar.
$formulary->appendChild('adminTools_security_sessionUserUnsubscribe');

// [todo] Remover ou congelar contas se inativas (após 6 meses de inatividade).
// [todo] O usuário pode desabilitar a recuperação de senha.

// O token para redefinir senha através de email é válido por tempo determinado (1 hora) e é válido uma única vez, sendo descartado ainda que haja tempo restante.
if(INTEGRATION_SMTP_ENABLE)
$formulary->appendChild('adminTools_security_sessionPasswordRecover', [
'ok' => 1,
'session_ttl' => SYSTEM_SESSION_TTL / 60,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'integrations', 'smtp', 'config']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_sessionPasswordRecover', [
'alert' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'integrations', 'smtp', 'config']),
'tips' => $tips
]);

// As senhas nunca podem ser decriptografadas, nem reveladas de volta ao usuário ao redefinir sua senha.
// [todo] Verificar periodicamente se as senhas dos usuários estão sendo embaralhadas utilizando as melhores técnicas disponíveis.
$formulary->appendChild('adminTools_security_sessionPasswordHash');

// Ao se cadastrar, o usuário deve informar uma frase de segurança. Assim, ao receber e-mails do sistema, será capaz de se certificar da origem da mensagem.
if(INTEGRATION_SMTP_ENABLE)
$formulary->appendChild('adminTools_security_sessionSecurityPhrase', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_sessionSecurityPhrase', ['alert' => 1]);

// [todo] O usuário pode fornecer uma chave pública PGP, que será utilizada para criptografar informações sensíveis enviadas por email, como links para redefinir senhas.

// Checar endereço de email enviando email com link de verificação.
if(INTEGRATION_SMTP_ENABLE)
$formulary->appendChild('adminTools_security_sessionEmailCheck', [
'ok' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'integrations', 'smtp', 'config']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_sessionEmailCheck', [
'alert' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'integrations', 'smtp', 'config']),
'tips' => $tips
]);

// Checar número de telefone enviando um SMS com código de verificação.
if(INTEGRATION_SMS_ENABLE)
$formulary->appendChild('adminTools_security_sessionPhoneCheck', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_sessionPhoneCheck', ['alert' => 1]);

// [todo] Checar CPF e nome de usuário contra um serviço de verificação.
// [todo] Solicitar periodicamente que o usuário verifique e atualize seus dados cadastrais.

if(!$admin)
return;

// == Política de conteúdo externo ==
$formulary->appendChild('adminTools_security_external');

// Não utilizar cookies externos
$formulary->appendChild('adminTools_security_externalCookies');

// Usar o header HTTP Content-Security-Policy para informar ao navegador quais assets (imagens, Javascripts, CSS, Frames, conexões socket) devem ser permitidos.
$item = $formulary->appendChild('adminTools_security_externalContentSecurityPolicy');
foreach(explode(' ', SYSTEM_CONTENT_SECURITY_POLICY) as $url)
{ // each url
$item->appendChild (['url' => $url]);
} // each url

// protocol Cuidar para que nenhum componente do documento seja requisitado via http, mas somente através de https.
if($document->protocol == 'https')
$formulary->appendChild('adminTools_security_externalProtocol', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_externalProtocol', ['fail' => 1]);

// styles
$formulary->appendChild('adminTools_security_externalStyles');

// scripts
$formulary->appendChild('adminTools_security_externalScripts');

// images
$formulary->appendChild('adminTools_security_externalImages');

// Fonts
$formulary->appendChild('adminTools_security_externalFonts');

// audios
$formulary->appendChild('adminTools_security_externalAudios');

// Vídeos
$formulary->appendChild('adminTools_security_externalVideos');

// files
$formulary->appendChild('adminTools_security_externalFiles');

// comments
$formulary->appendChild('adminTools_security_externalComments');

// [todo] O sistema deve ter seus próprios mecanismos de rastreio e estatísticas de acesso.
// [todo] Evitar ao máximo usar as diretivas unsafe-inline e unsafe-eval. 
// [todo] Garantir que funções eval(), innerHTML, document.write() não sejam brecha para ataques XSS. 
// [todo] Comentários em arquivos não revelam informações sensíveis.
// [todo] Cuidar para que iframes sejam criados a partir de fontes filtradas, Impedindo que conteúdos de sites maliciosos sejam incluídos no documento (frame spoofing).
// [todo] Todas as páginas são exibidas com barra de endereço e barra de status.
// [todo] Links para outros sites são abertos em novas janelas ou abas, isolando o javascript (rel='noopener noreferrer').
// [todo] Conteúdos em iframes de outras origens devem ser isolados para evitar XSS.

// == Banco de dados ==
$formulary->appendChild('adminTools_security_database');

// Antes de serem enviados ao banco de dados, os dados devem ser filtrados de acordo com a coluna: inteiros, strings, hashes, dados empacotados e assim por diante. Esta verificação deve ser feita na camada mais interna, de forma que a verificação não dependa do módulo que envia os dados.
// Um mecanismo seguro garante que caracteres especiais entram e saiam do banco de dados com segurança, incluindo bytes nulos e barras invertidas (SQL injection).
$formulary->appendChild('adminTools_security_databaseSanitization');

// Utilizar criptografia em banco de dados para armazenar dados sensíveis.
if(DATABASE_ENCRYPT_ENABLE)
$formulary->appendChild('adminTools_security_databaseEncrypt', [
'ok' => 1,
'cipher' => DATABASE_ENCRYPT_CIPHER,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'encrypt']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_databaseEncrypt', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'encrypt']),
'tips' => $tips
]);

// Colunas de dados sensíveis que precisam ser pesquisadas recebem dados embaralhados de forma segura.
if(DATABASE_ENCRYPT_ENABLE)
$formulary->appendChild('adminTools_security_databaseHashing', [
'ok' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'encrypt']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_databaseHashing', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'encrypt']),
'tips' => $tips
]);

// O banco de dados está configurado para não exibir erros através do navegador.
if(!DATABASE_DISPLAY_ERRORS)
$formulary->appendChild('adminTools_security_databaseDisplayErrors', [
'ok' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'log']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_databaseDisplayErrors', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'log']),
'tips' => $tips
]);

// O banco de dados está configurado para registrar erros em arquivo de log.
if(DATABASE_LOG_ERRORS)
$formulary->appendChild('adminTools_security_databaseLogErrors', [
'ok' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'log']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_databaseLogErrors', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'database', 'log']),
'tips' => $tips
]);

// == Cookies ==
$formulary->appendChild('adminTools_security_cookies');

// O uso de Cookies para identificar a seção do usuário deve estar ativado. Outros métodos de identificação como SID através de post ou get não devem ser aceitos.
$formulary->appendChild('adminTools_security_cookiesEnabled');

// Gerar cookies somente com conteúdo seguro (HTTP response splitting, Cross-User Defacement, Cache Poisoning).
// Nenhum dado sensível do usuário deve ser armazenado no computador do cliente.
// Tokens de sessão devem ter pelo menos 128 bits.
// Tokens de sessão devem ser imprevisíveis (aleatórios).
$formulary->appendChild('adminTools_security_cookiesGeneration');

// Os cookies devem estar configurados para não serem acessíveis através de JavaScript (HttpOnly).
// Os cookies devem ser configurados para serem eliminados ao fechar o navegador.
// Os navegadores só devem devolver os cookies em situações restritas (SameSite=Strict).
$host = substr ($document->host, 0, strpos ($document->host, '/'));
$formulary->appendChild('adminTools_security_cookiesConfiguration', ['host' => $host]);

// Os cookies são considerados válidos somente se procederem do mesmo endereço IP e do mesmo navegador.
// Quando o usuário se conecta, o token de sessão deve ser  atualizado.
// Quando o usuário desconecta, a sessão é destruída no servidor.
$formulary->appendChild('adminTools_security_cookiesManagement');

// Os cookies só devem trafegar por conexões seguras (TLS) (Secure).
if($document->protocol == 'https')
$formulary->appendChild('adminTools_security_cookiesSecure', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_cookiesSecure', ['fail' => 1]);

// Armazenamento de dados de sessão
if(DATABASE_ENABLE)
$formulary->appendChild('adminTools_security_cookiesStore', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_cookiesStore', ['fail' => 1]);

// Os dados de sessão são criptografados utilizando tokens enviados através dos cookies. Portanto, nenhuma chave para desencriptar os dados das sessões permanecem no servidor.
if(DATABASE_ENCRYPT_ENABLE)
$formulary->appendChild('adminTools_security_cookiesEncryption', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_cookiesEncryption', ['fail' => 1]);

// == Formulários ==
$formulary->appendChild('adminTools_security_formularies');

// Cada campo de formulário deve ter seu próprio filtro, que realiza a checagem dos dados recebidos no lado do servidor.
// Tentativas de injetar comandos SQL ou cabeçalhos extras de email nos formulários devem ser rejeitadas (Null-byte injection). Se bem que inserir bytes nulos ou barras invertidas não deve causar nenhum efeito quando o mecanismo de armazenamento do banco de dados é seguro.
// Em caso de preenchimento inadequado, os formulários são remetidos de volta ao usuário, com mensagens detalhadas indicando com clareza o campo e o motivo do problema.
$formulary->appendChild('adminTools_security_formulariesFilters');

// [todo] Autocomplete=off para todos os campos de dados pessoais, impedindo que outros usuários descubram seus dados.
// [todo] Utilizar IDs dinâmicos para campos de formulários.

// Uso de campos ocultos (Roneypot) para identificar preenchimento por robôs.
// Usar token inserido no formulário por javascript impede que robôs que não executam javascript submetam o formulário. Os tokens são criados na sessão do usuário.
// Não aceitar formulários sem HTTP Referer da página original e sem retorno de cookie de sessão.
// Após o preenchimento do formulário, o usuário recebe uma página para conferir os dados postados e confirmar o envio.
$formulary->appendChild('adminTools_security_formulariesCrawlers');

// Para impedir que robôs capturem endereços de emails nos documentos, um mecanismo de ofuscamento oculta estes endereços aos robôs, usando javascript para desembaralhar os endereços e os escrever no documento.
$formulary->appendChild('adminTools_security_formulariesEmailObfuscation');

// [todo] Não permitir que informações sensíveis circulem através de campos de formulário ocultos, tais como identificação do usuário ou preço de produto (Parameter tampering).
// [todo] Não permitir que comandos enviados através de parâmetros da URL sejam executados sem as devidas verificações e permições (Parameter tampering).
// [todo] Checar pelo tipo de arquivo recebido no servidor baseado no conteúdo do arquivo e não na extensão.

// == Sistema ==
$formulary->appendChild('adminTools_security_engine');

// Em ambiente de produção, erros de scripts (PHP, Perl, Ruby, etc) não devem ser enviados para o navegador.
if(!SYSTEM_DISPLAY_ERRORS)
$formulary->appendChild('adminTools_security_engineDisplayErrors', [
'ok' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_engineDisplayErrors', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);

// Erros de scripts no servidor devem ser registrados em arquivo de log.
if(SYSTEM_LOG_ERRORS)
$formulary->appendChild('adminTools_security_engineLogErrors', [
'ok' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_engineLogErrors', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);

// Todos os tipos de erros devem ser registrados (E_ALL).
$formulary->appendChild('adminTools_security_engineAllErrors');

// O tempo de execução de scripts no servidor deve ser limitado (3 segundos para tarefas corriqueiras).
if(SYSTEM_TIME_LIMIT)
$formulary->appendChild('adminTools_security_engineTimeLimit', [
'ok' => 1,
'time' => SYSTEM_TIME_LIMIT,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_engineTimeLimit', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);

// Garantir que conteúdos HTML editados pelo usuário não contenham tags PHP que possam ser executados no servidor.
$formulary->appendChild('adminTools_security_engineUserHtml');

// Não utilizar uma conta "admin" para o administrador
if(ADMIN_IDENTIFIER != 'admin')
$formulary->appendChild('adminTools_security_engineAdminIdentifier', [
'ok' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'admin']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_engineAdminIdentifier', [
'fail' => 1,
'url_edit' => $document->url ([SYSTEM_ADMIN_URI, 'system', 'admin']),
'tips' => $tips
]);

// == Requisições e sistema de arquivos ==
$formulary->appendChild('adminTools_security_request');

// Aceitar somente conexões seguras (TLS) - Usar o header HTTP Strict-Transport-Security para instruir ao navegador que sempre se conecte a versão HTTPS do site.
if(SYSTEM_HTTPS_REDIRECT)
$formulary->appendChild('adminTools_security_requestSecure', [
'ok' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'server']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_requestSecure', [
'fail' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'server']),
'tips' => $tips
]);

// Rejeitar requisições que possam conter scripts e injeção de variáveis - bloquear requisições contendo "<[]:@?=%&".
if($document->rewriteEngine)
$formulary->appendChild('adminTools_security_requestRewriteEngineCharsBlock', [
'ok' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'server']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_requestRewriteEngineCharsBlock', [
'fail' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'server']),
'tips' => $tips
]);

// Utilizar técnicas de "RewriteEngines" para ocultar detalhes sobre a estrutura interna do sistema.
// Utilizar técnicas de "RewriteEngines" para transformar URLs dinâmicas em URLs estáticas.
// Bloquear acesso a pastas do sistema através de diretivas .htaccess (Google Hacking )
if($document->rewriteEngine)
$formulary->appendChild('adminTools_security_requestRewriteEngineRedirection', [
'ok' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'server']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_requestRewriteEngineRedirection', [
'fail' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'server']),
'tips' => $tips
]);

// Bloquear acesso a pastas do sistema com chmod
// Não manter no servidor arquivos órfãos - que não estejam devidamente linkados.
// Manter uma estrutura de pastas simples e enxuta.

// [todo] Não permitir acesso a arquivos de backup que possam revelar códigos fonte ou dados sensíveis.
// [todo] Retirar do servidor os arquivos de backup o mais rápido possível.

// Impedir que usuários façam upload de scripts para o servidor que possam ser executados (Server-side script injection (PHP, Perl, etc.)).
// Alterar as extensões de arquivos enviados pelos usuários evitando que sejam executados pelo servidor.
// Verificar direito de acesso ao receber requisições para downloads de arquivos protegidos.

// Impedir que os navegadores e outros servidores façam cache de arquivos baixados quando estes arquivos forem protegidos, utilizando cabeçalhos adequados.
// Impedir que navegadores e servidores façam cache de páginas protegidas.
$formulary->appendChild('adminTools_security_requestNoCache');

// == Manutenção ==
$formulary->appendChild('adminTools_security_maintenance');

// Utilizar as versões mais recentes do PHP, MySQL etc.
$formulary->appendChild('adminTools_security_maintenancePhpVersion', [
'server_software' => $_SERVER['SERVER_SOFTWARE'],
'php_version' => PHP_VERSION,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'tools', 'php-info']),
'tips' => $tips
]);

// Consultar log de erros do PHP
if(!is_file(SYSTEM_LOG_FILE))
$formulary->appendChild('adminTools_security_maintenanceLogPhp', [
'ok' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_maintenanceLogPhp', [
'fail' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'log']),
'tips' => $tips
]);

// Consultar log de erros do banco de dados
if(!is_file(DATABASE_LOG_FILE))
$formulary->appendChild('adminTools_security_maintenanceLogDatabase', [
'ok' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'database', 'log']),
'tips' => $tips
]);
else
$formulary->appendChild('adminTools_security_maintenanceLogDatabase', [
'fail' => 1,
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'database', 'log']),
'tips' => $tips
]);

// Excluir componentes, temas, bibliotecas e outros recursos que não estejam sendo utilizados.
// Manter sempre atualizado o sistema e todos os plugins e componentes envolvidos.

// Garantir que a comunicação com o sistema e serviços internos seja baseada em conteúdo sanitizado (Shell script / batch injection, Directory traversal, Mail header injection, XPATH injection, LDAP injection, LIKE pattern injection, SQL injection...).
// Garantir que funções eval() não coloquem o sistema em risco (script injection).
// Utilizar bibliotecas seguras ao se conectar a serviços externos, como a CURL.
// Dar preferência a serviços externos que utilizem protocolos seguros (TLS).
// Desabilitar funcionalidades que não estejam sendo utilizadas (REST API, etc).
// Utilizar as melhores  bibliotecas e técnicas de criptografia disponíveis.
// Não realizar cache de dados sensíveis.
// Consultar periodicamente o consumo de recursos do sistema contra os recursos disponíveis.
// O sistema deve emitir avisos ao administrador de que existem registros nos arquivos de log.
// Guardar os dados de acesso do administrador em local seguro, arquivo protegido por senha.

// Revise periodicamente as configurações do servidor.
// Revise periodicamente as configurações do firewall.
// Escanear periodicamente o servidor a procura de vírus, malwares e falhas de segurança
// Manter todos os softwares do servidor atualizados.
// Realizar backups periódicos.
// Testar a restauração a partir dos arquivos de backup.
// Conectar-se ao servidor somente através de protocolos seguros como o SFTP, SCP ou SSH.
// Armazene dados de acesso ao servidor em local seguro, por exemplo arquivo criptografado com senha.
// Armazene suas chaves com direitos 0500
// Tenha certeza sobre quais pessoas possuem direito de acesso ao servidor.
// Altere periodicamente todas as chaves e senhas de todos os administradores.
// Remova contas inativas de administradores.
// Verifique se o log de eventos está configurado corretamente.
// Verifique se todos os logins estão sendo registrados no log.
// Verifique se todas as mudanças nas configurações estão sendo registradas no log.
// Verifique se estão sendo feitos backups dos arquivos de log.
// Consulte periodicamente as atividades no servidor através dos logs e de outras ferramentas de registro e estatísticas, consumo de recursos e conexões.
// Documente ações suspeitas, horários e quais dados estão sendo acessados.
// Bloqueie contas agindo de maneira suspeita e contate seu responsável.




} // function create

static function save ($fieldName, $control, $formulary)
{ // function save

} // function save

} // class eclFilter_adminTools_security_list

?>