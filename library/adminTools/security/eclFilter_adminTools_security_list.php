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

// == Se��o ==
$formulary->appendChild('adminTools_security_session');

// [todo] Disp�r de mecanismos para evitar ataques por for�a bruta.
// [todo] Bloquear usu�rio ap�s um n�mero de tentativas malogradas de login.
// [todo] N�o permitir que o usu�rio desbloqueie a conta bloqueada - por exemplo removendo os cookies.

// Informar ao usu�rio sobre a for�a da senha escolhida, Exigir senhas com pelo menos 6 caracteres, contendo letras mai�sculas, min�sculas e n�meros. Caracteres especiais tamb�m devem ser aceitos e o comprimento da senha n�o deve ser limitado.
$formulary->appendChild('adminTools_security_sessionPasswordStrengthCheck');

// Caso haja falha no login, n�o deixar transparecer se o usu�rio existe ou n�o cadastrado no sistema.
$formulary->appendChild('adminTools_security_sessionLoginFailureReport');

// [todo] Prevenir ataques por recarga, evitando que o navegador reenvie os dados de login.

// Lembrar o usu�rio de alterar sua senha periodicamente (a cada 6 meses).
$formulary->appendChild('adminTools_security_sessionPasswordUpdate');

// Informar ao usu�rio a data do seu �ltimo acesso, permitindo conferir se foi ele mesmo que se conectou.
$formulary->appendChild('adminTools_security_sessionLoginLastAccess');

// Expirar a sess�o do usu�rio ap�s um tempo limite de inatividade (30 minutos).
$formulary->appendChild('adminTools_security_sessionExpires', [
'url_edit' => $document->url([SYSTEM_ADMIN_URI, 'system', 'setup']),
'session_ttl' => SYSTEM_SESSION_TTL / 60,
'tips' => $tips
]);

// [todo] Expirar a sess�o do usu�rio ap�s um tempo absoluto de atividade (12 horas).

// Disponibilizar link para desconectar.
$formulary->appendChild('adminTools_security_sessionLogout');

// Disponibilizar link para redefinir senha.
$formulary->appendChild('adminTools_security_sessionPasswordChange');

// Disponibilizar link para descadastrar.
$formulary->appendChild('adminTools_security_sessionUserUnsubscribe');

// [todo] Remover ou congelar contas se inativas (ap�s 6 meses de inatividade).
// [todo] O usu�rio pode desabilitar a recupera��o de senha.

// O token para redefinir senha atrav�s de email � v�lido por tempo determinado (1 hora) e � v�lido uma �nica vez, sendo descartado ainda que haja tempo restante.
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

// As senhas nunca podem ser decriptografadas, nem reveladas de volta ao usu�rio ao redefinir sua senha.
// [todo] Verificar periodicamente se as senhas dos usu�rios est�o sendo embaralhadas utilizando as melhores t�cnicas dispon�veis.
$formulary->appendChild('adminTools_security_sessionPasswordHash');

// Ao se cadastrar, o usu�rio deve informar uma frase de seguran�a. Assim, ao receber e-mails do sistema, ser� capaz de se certificar da origem da mensagem.
if(INTEGRATION_SMTP_ENABLE)
$formulary->appendChild('adminTools_security_sessionSecurityPhrase', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_sessionSecurityPhrase', ['alert' => 1]);

// [todo] O usu�rio pode fornecer uma chave p�blica PGP, que ser� utilizada para criptografar informa��es sens�veis enviadas por email, como links para redefinir senhas.

// Checar endere�o de email enviando email com link de verifica��o.
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

// Checar n�mero de telefone enviando um SMS com c�digo de verifica��o.
if(INTEGRATION_SMS_ENABLE)
$formulary->appendChild('adminTools_security_sessionPhoneCheck', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_sessionPhoneCheck', ['alert' => 1]);

// [todo] Checar CPF e nome de usu�rio contra um servi�o de verifica��o.
// [todo] Solicitar periodicamente que o usu�rio verifique e atualize seus dados cadastrais.

if(!$admin)
return;

// == Pol�tica de conte�do externo ==
$formulary->appendChild('adminTools_security_external');

// N�o utilizar cookies externos
$formulary->appendChild('adminTools_security_externalCookies');

// Usar o header HTTP Content-Security-Policy para informar ao navegador quais assets (imagens, Javascripts, CSS, Frames, conex�es socket) devem ser permitidos.
$item = $formulary->appendChild('adminTools_security_externalContentSecurityPolicy');
foreach(explode(' ', SYSTEM_CONTENT_SECURITY_POLICY) as $url)
{ // each url
$item->appendChild (['url' => $url]);
} // each url

// protocol Cuidar para que nenhum componente do documento seja requisitado via http, mas somente atrav�s de https.
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

// V�deos
$formulary->appendChild('adminTools_security_externalVideos');

// files
$formulary->appendChild('adminTools_security_externalFiles');

// comments
$formulary->appendChild('adminTools_security_externalComments');

// [todo] O sistema deve ter seus pr�prios mecanismos de rastreio e estat�sticas de acesso.
// [todo] Evitar ao m�ximo usar as diretivas unsafe-inline e unsafe-eval. 
// [todo] Garantir que fun��es eval(), innerHTML, document.write() n�o sejam brecha para ataques XSS. 
// [todo] Coment�rios em arquivos n�o revelam informa��es sens�veis.
// [todo] Cuidar para que iframes sejam criados a partir de fontes filtradas, Impedindo que conte�dos de sites maliciosos sejam inclu�dos no documento (frame spoofing).
// [todo] Todas as p�ginas s�o exibidas com barra de endere�o e barra de status.
// [todo] Links para outros sites s�o abertos em novas janelas ou abas, isolando o javascript (rel='noopener noreferrer').
// [todo] Conte�dos em iframes de outras origens devem ser isolados para evitar XSS.

// == Banco de dados ==
$formulary->appendChild('adminTools_security_database');

// Antes de serem enviados ao banco de dados, os dados devem ser filtrados de acordo com a coluna: inteiros, strings, hashes, dados empacotados e assim por diante. Esta verifica��o deve ser feita na camada mais interna, de forma que a verifica��o n�o dependa do m�dulo que envia os dados.
// Um mecanismo seguro garante que caracteres especiais entram e saiam do banco de dados com seguran�a, incluindo bytes nulos e barras invertidas (SQL injection).
$formulary->appendChild('adminTools_security_databaseSanitization');

// Utilizar criptografia em banco de dados para armazenar dados sens�veis.
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

// Colunas de dados sens�veis que precisam ser pesquisadas recebem dados embaralhados de forma segura.
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

// O banco de dados est� configurado para n�o exibir erros atrav�s do navegador.
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

// O banco de dados est� configurado para registrar erros em arquivo de log.
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

// O uso de Cookies para identificar a se��o do usu�rio deve estar ativado. Outros m�todos de identifica��o como SID atrav�s de post ou get n�o devem ser aceitos.
$formulary->appendChild('adminTools_security_cookiesEnabled');

// Gerar cookies somente com conte�do seguro (HTTP response splitting, Cross-User Defacement, Cache Poisoning).
// Nenhum dado sens�vel do usu�rio deve ser armazenado no computador do cliente.
// Tokens de sess�o devem ter pelo menos 128 bits.
// Tokens de sess�o devem ser imprevis�veis (aleat�rios).
$formulary->appendChild('adminTools_security_cookiesGeneration');

// Os cookies devem estar configurados para n�o serem acess�veis atrav�s de JavaScript (HttpOnly).
// Os cookies devem ser configurados para serem eliminados ao fechar o navegador.
// Os navegadores s� devem devolver os cookies em situa��es restritas (SameSite=Strict).
$host = substr ($document->host, 0, strpos ($document->host, '/'));
$formulary->appendChild('adminTools_security_cookiesConfiguration', ['host' => $host]);

// Os cookies s�o considerados v�lidos somente se procederem do mesmo endere�o IP e do mesmo navegador.
// Quando o usu�rio se conecta, o token de sess�o deve ser  atualizado.
// Quando o usu�rio desconecta, a sess�o � destru�da no servidor.
$formulary->appendChild('adminTools_security_cookiesManagement');

// Os cookies s� devem trafegar por conex�es seguras (TLS) (Secure).
if($document->protocol == 'https')
$formulary->appendChild('adminTools_security_cookiesSecure', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_cookiesSecure', ['fail' => 1]);

// Armazenamento de dados de sess�o
if(DATABASE_ENABLE)
$formulary->appendChild('adminTools_security_cookiesStore', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_cookiesStore', ['fail' => 1]);

// Os dados de sess�o s�o criptografados utilizando tokens enviados atrav�s dos cookies. Portanto, nenhuma chave para desencriptar os dados das sess�es permanecem no servidor.
if(DATABASE_ENCRYPT_ENABLE)
$formulary->appendChild('adminTools_security_cookiesEncryption', ['ok' => 1]);
else
$formulary->appendChild('adminTools_security_cookiesEncryption', ['fail' => 1]);

// == Formul�rios ==
$formulary->appendChild('adminTools_security_formularies');

// Cada campo de formul�rio deve ter seu pr�prio filtro, que realiza a checagem dos dados recebidos no lado do servidor.
// Tentativas de injetar comandos SQL ou cabe�alhos extras de email nos formul�rios devem ser rejeitadas (Null-byte injection). Se bem que inserir bytes nulos ou barras invertidas n�o deve causar nenhum efeito quando o mecanismo de armazenamento do banco de dados � seguro.
// Em caso de preenchimento inadequado, os formul�rios s�o remetidos de volta ao usu�rio, com mensagens detalhadas indicando com clareza o campo e o motivo do problema.
$formulary->appendChild('adminTools_security_formulariesFilters');

// [todo] Autocomplete=off para todos os campos de dados pessoais, impedindo que outros usu�rios descubram seus dados.
// [todo] Utilizar IDs din�micos para campos de formul�rios.

// Uso de campos ocultos (Roneypot) para identificar preenchimento por rob�s.
// Usar token inserido no formul�rio por javascript impede que rob�s que n�o executam javascript submetam o formul�rio. Os tokens s�o criados na sess�o do usu�rio.
// N�o aceitar formul�rios sem HTTP Referer da p�gina original e sem retorno de cookie de sess�o.
// Ap�s o preenchimento do formul�rio, o usu�rio recebe uma p�gina para conferir os dados postados e confirmar o envio.
$formulary->appendChild('adminTools_security_formulariesCrawlers');

// Para impedir que rob�s capturem endere�os de emails nos documentos, um mecanismo de ofuscamento oculta estes endere�os aos rob�s, usando javascript para desembaralhar os endere�os e os escrever no documento.
$formulary->appendChild('adminTools_security_formulariesEmailObfuscation');

// [todo] N�o permitir que informa��es sens�veis circulem atrav�s de campos de formul�rio ocultos, tais como identifica��o do usu�rio ou pre�o de produto (Parameter tampering).
// [todo] N�o permitir que comandos enviados atrav�s de par�metros da URL sejam executados sem as devidas verifica��es e permi��es (Parameter tampering).
// [todo] Checar pelo tipo de arquivo recebido no servidor baseado no conte�do do arquivo e n�o na extens�o.

// == Sistema ==
$formulary->appendChild('adminTools_security_engine');

// Em ambiente de produ��o, erros de scripts (PHP, Perl, Ruby, etc) n�o devem ser enviados para o navegador.
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

// O tempo de execu��o de scripts no servidor deve ser limitado (3 segundos para tarefas corriqueiras).
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

// Garantir que conte�dos HTML editados pelo usu�rio n�o contenham tags PHP que possam ser executados no servidor.
$formulary->appendChild('adminTools_security_engineUserHtml');

// N�o utilizar uma conta "admin" para o administrador
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

// == Requisi��es e sistema de arquivos ==
$formulary->appendChild('adminTools_security_request');

// Aceitar somente conex�es seguras (TLS) - Usar o header HTTP Strict-Transport-Security para instruir ao navegador que sempre se conecte a vers�o HTTPS do site.
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

// Rejeitar requisi��es que possam conter scripts e inje��o de vari�veis - bloquear requisi��es contendo "<[]:@?=%&".
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

// Utilizar t�cnicas de "RewriteEngines" para ocultar detalhes sobre a estrutura interna do sistema.
// Utilizar t�cnicas de "RewriteEngines" para transformar URLs din�micas em URLs est�ticas.
// Bloquear acesso a pastas do sistema atrav�s de diretivas .htaccess (Google Hacking )
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
// N�o manter no servidor arquivos �rf�os - que n�o estejam devidamente linkados.
// Manter uma estrutura de pastas simples e enxuta.

// [todo] N�o permitir acesso a arquivos de backup que possam revelar c�digos fonte ou dados sens�veis.
// [todo] Retirar do servidor os arquivos de backup o mais r�pido poss�vel.

// Impedir que usu�rios fa�am upload de scripts para o servidor que possam ser executados (Server-side script injection (PHP, Perl, etc.)).
// Alterar as extens�es de arquivos enviados pelos usu�rios evitando que sejam executados pelo servidor.
// Verificar direito de acesso ao receber requisi��es para downloads de arquivos protegidos.

// Impedir que os navegadores e outros servidores fa�am cache de arquivos baixados quando estes arquivos forem protegidos, utilizando cabe�alhos adequados.
// Impedir que navegadores e servidores fa�am cache de p�ginas protegidas.
$formulary->appendChild('adminTools_security_requestNoCache');

// == Manuten��o ==
$formulary->appendChild('adminTools_security_maintenance');

// Utilizar as vers�es mais recentes do PHP, MySQL etc.
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

// Excluir componentes, temas, bibliotecas e outros recursos que n�o estejam sendo utilizados.
// Manter sempre atualizado o sistema e todos os plugins e componentes envolvidos.

// Garantir que a comunica��o com o sistema e servi�os internos seja baseada em conte�do sanitizado (Shell script / batch injection, Directory traversal, Mail header injection, XPATH injection, LDAP injection, LIKE pattern injection, SQL injection...).
// Garantir que fun��es eval() n�o coloquem o sistema em risco (script injection).
// Utilizar bibliotecas seguras ao se conectar a servi�os externos, como a CURL.
// Dar prefer�ncia a servi�os externos que utilizem protocolos seguros (TLS).
// Desabilitar funcionalidades que n�o estejam sendo utilizadas (REST API, etc).
// Utilizar as melhores  bibliotecas e t�cnicas de criptografia dispon�veis.
// N�o realizar cache de dados sens�veis.
// Consultar periodicamente o consumo de recursos do sistema contra os recursos dispon�veis.
// O sistema deve emitir avisos ao administrador de que existem registros nos arquivos de log.
// Guardar os dados de acesso do administrador em local seguro, arquivo protegido por senha.

// Revise periodicamente as configura��es do servidor.
// Revise periodicamente as configura��es do firewall.
// Escanear periodicamente o servidor a procura de v�rus, malwares e falhas de seguran�a
// Manter todos os softwares do servidor atualizados.
// Realizar backups peri�dicos.
// Testar a restaura��o a partir dos arquivos de backup.
// Conectar-se ao servidor somente atrav�s de protocolos seguros como o SFTP, SCP ou SSH.
// Armazene dados de acesso ao servidor em local seguro, por exemplo arquivo criptografado com senha.
// Armazene suas chaves com direitos 0500
// Tenha certeza sobre quais pessoas possuem direito de acesso ao servidor.
// Altere periodicamente todas as chaves e senhas de todos os administradores.
// Remova contas inativas de administradores.
// Verifique se o log de eventos est� configurado corretamente.
// Verifique se todos os logins est�o sendo registrados no log.
// Verifique se todas as mudan�as nas configura��es est�o sendo registradas no log.
// Verifique se est�o sendo feitos backups dos arquivos de log.
// Consulte periodicamente as atividades no servidor atrav�s dos logs e de outras ferramentas de registro e estat�sticas, consumo de recursos e conex�es.
// Documente a��es suspeitas, hor�rios e quais dados est�o sendo acessados.
// Bloqueie contas agindo de maneira suspeita e contate seu respons�vel.




} // function create

static function save ($fieldName, $control, $formulary)
{ // function save

} // function save

} // class eclFilter_adminTools_security_list

?>