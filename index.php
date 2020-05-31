<?php

$startTime = microtime(true);
ob_start();

define ('SYSTEM_CONFIG_FILE', 'config.php');

$aliases = [];
$components = [];
$map = [];
$mapPrepend = [];
$mapAppend = [];
$project = [];

!is_file(__DIR__ . '/' . SYSTEM_CONFIG_FILE) or include __DIR__ . '/' . SYSTEM_CONFIG_FILE;

defined ('FOLDER_COMPONENTS') or define ('FOLDER_COMPONENTS', 'components/');
defined ('FOLDER_DATABASE') or define ('FOLDER_DATABASE', 'database/');
defined ('FOLDER_DOMAINS') or define ('FOLDER_DOMAINS', 'domains/');
defined ('FOLDER_ENGINE') or define ('FOLDER_ENGINE', 'engine/');
defined ('FOLDER_LIBRARY') or define ('FOLDER_LIBRARY', 'library/');
defined ('FOLDER_PROFILES') or define ('FOLDER_PROFILES', 'profiles/');
defined ('FOLDER_SHARED') or define ('FOLDER_SHARED', 'shared/');
defined ('FOLDER_TEMPLATES') or define ('FOLDER_TEMPLATES', 'templates/');

define ('PATH_ROOT', __DIR__ . '/');
define ('PATH_COMPONENTS', __DIR__ . '/' . FOLDER_COMPONENTS);
define ('PATH_DATABASE', __DIR__ . '/' . FOLDER_DATABASE);
define ('PATH_DOMAINS', __DIR__ . '/' . FOLDER_DOMAINS);
define ('PATH_ENGINE', __DIR__ . '/' . FOLDER_ENGINE);
define ('PATH_LIBRARY', __DIR__ . '/' . FOLDER_LIBRARY);
define ('PATH_PROFILES', __DIR__ . '/' . FOLDER_PROFILES);
define ('PATH_SHARED', __DIR__ . '/' . FOLDER_SHARED);
define ('PATH_TEMPLATES', __DIR__ . '/' . FOLDER_TEMPLATES);

defined ('SYSTEM_HOSTING_MODE') or define ('SYSTEM_HOSTING_MODE', 0);
defined ('SYSTEM_HOST') or define ('SYSTEM_HOST', 'localhost');
defined ('SYSTEM_DEFAULT_DOMAIN_NAME') or define ('SYSTEM_DEFAULT_DOMAIN_NAME', 'admin');
defined ('SYSTEM_ADMIN_URI') or define ('SYSTEM_ADMIN_URI', 'admin');
defined ('SYSTEM_PROFILES_URI') or define ('SYSTEM_PROFILES_URI', 'profiles');
defined ('SYSTEM_ENABLE_USER_SUBSCRIPTIONS') or define ('SYSTEM_ENABLE_USER_SUBSCRIPTIONS', false);
defined ('SYSTEM_ENABLE_USER_DOMAINS') or define ('SYSTEM_ENABLE_USER_DOMAINS', false);
defined ('SYSTEM_DEFAULT_LANGUAGE') or define ('SYSTEM_DEFAULT_LANGUAGE', 'pt');
defined ('SYSTEM_DEFAULT_CURRENCY') or define ('SYSTEM_DEFAULT_CURRENCY', 'BRL');
defined ('SYSTEM_DEFAULT_CHARSET') or define ('SYSTEM_DEFAULT_CHARSET', 'UTF-8');
defined ('SYSTEM_SESSION_TTL') or define ('SYSTEM_SESSION_TTL', 3600);
defined ('SYSTEM_SESSION_CACHE_EXPIRE') or define ('SYSTEM_SESSION_CACHE_EXPIRE', 300);
defined ('SYSTEM_TIME_LIMIT') or define ('SYSTEM_TIME_LIMIT', 6);
defined ('SYSTEM_TIMEZONE') or define ('SYSTEM_TIMEZONE', 'America/Sao_Paulo');
defined ('SYSTEM_DISPLAY_ERRORS') or define('SYSTEM_DISPLAY_ERRORS', 1);
defined ('SYSTEM_LOG_ERRORS') or define ('SYSTEM_LOG_ERRORS', 0);
defined ('SYSTEM_HTTPS_REDIRECT') or define ('SYSTEM_HTTPS_REDIRECT', false);
defined ('SYSTEM_REWRITE_ENGINE') or define ('SYSTEM_REWRITE_ENGINE', false);
defined ('SYSTEM_CONTENT_SECURITY_POLICY') or define ('SYSTEM_CONTENT_SECURITY_POLICY', 'https://*.ecolabore.net https://viacep.com.br');

defined ('DATABASE_ENABLE') or define ('DATABASE_ENABLE', false);
defined ('DATABASE_DB') or define ('DATABASE_DB', 'ecolabore');
defined ('DATABASE_PREFIX') or define ('DATABASE_PREFIX', 'ecl_');
defined ('DATABASE_LOG_ERRORS') or define ('DATABASE_LOG_ERRORS', true);
defined ('DATABASE_DISPLAY_ERRORS') or define ('DATABASE_DISPLAY_ERRORS', false);
define ('DATABASE_LOG_FILE', __DIR__ . '/.log_database_errors');

defined ('INTEGRATION_SMS_ENABLE') or define ('INTEGRATION_SMS_ENABLE', false);
defined ('INTEGRATION_SMTP_ENABLE') or define ('INTEGRATION_SMTP_ENABLE', false);
defined ('INTEGRATION_SMTP_TTL') or define ('INTEGRATION_SMTP_TTL', 10);

define ('SYSTEM_SCRIPT_PATH', __FILE__);
define ('SYSTEM_SCRIPT_NAME', substr (__FILE__, 1 + strlen (__DIR__)));
define ('SYSTEM_COMPILER_HALT_OFFSET', __COMPILER_HALT_OFFSET__);
define ('SYSTEM_LOG_FILE', __DIR__ . '/.log_php_errors');

/* Ecolabore Engine Copyright(c)Angelo Beck 2019 - GPL3 All rights reversed */
defined('ECOLABORE_ENGINE_UPDATE_URL') or define ('ECOLABORE_ENGINE_UPDATE_URL', 'https://engine.ecolabore.net/-endpoints/ecolabore-update/v4');
define ('ECOLABORE_LOG_FILE', __DIR__ . '/.log_ecolabore_errors');
define('ECOLABORE_DATA', [
'text' => [
'caption' => [
'pt' => [1 => 'Portal Ecolabore'],
'en' => [1 => 'Ecolabore Portal']
]
],
'local' => [
'gender' => 'male',
'generator' => 'Ecolabore Engine',
'version' => '4',
'release' => '2020-05-18',
'url' => 'https://ecolabore.net',
]
]);

define ('MODE_DOMAIN', 1);
define ('MODE_SECTION', 2);
define ('MODE_POST', 3);
define ('MODE_KEYWORD', 4);
define ('MODE_FORM', 5);
define ('MODE_COMMENT', 6);
define ('MODE_TEMPLATE', 7);
define ('MODE_CONFIG', 8);
define ('MODE_ABUSE_REPORT', 9);
define ('MODE_GROUP', 32);
define ('MODE_SUBGROUP', 33);
define ('MODE_SUBSCRIPTION', 34);
define ('MODE_FOLDER', 35);
define ('MODE_DOC', 36);
define ('MODE_VERSION', 37);
define ('MODE_BRANCH', 40);
define ('MODE_PLACE', 41);
define ('MODE_FINANCIAL', 50);
define ('MODE_ACCOUNT', 51);
define ('MODE_CONTRACT', 52);
define ('MODE_TRANSACTION', 53);
define ('MODE_ORDER', 54);
define ('MODE_PRODUCT', 55);
define ('MODE_MODULE', 64);
define ('MODE_DISCIPLINE', 65);
define ('MODE_LESSON', 66);
define ('MODE_CLASS', 67);
define ('MODE_MESSAGE', 70);

define ('STATUS_CONNECTED', 1);
define ('STATUS_MEMBER', 2);
define ('STATUS_ADMIN', 4);
define ('STATUS_EMPTY', 8);
define ('STATUS_UPDATE', 16);
define ('STATUS_ALERT', 32);
define ('STATUS_SURVEY', 64);
define ('STATUS_REMOVED', 128);

define ('TEXT_CONTENT', 1);
define ('TEXT_CHARSET', 2); // 0=UTF-8 1=ISO-8859-1
define ('TEXT_LANG', 3);
define ('TEXT_FORMAT', 4); // 0=no 1=format 2=pre
define ('TEXT_HTML', 5); // 0=no 1=filter 2=free
define ('TEXT_ECOLABORE', 6); // 0=no 1=yes
define ('TEXT_EDITABLE', 7);

defined ('CHR_FNS') or define ('CHR_FNS', '+');
define ('CR', chr(13));
define ('LF', chr(10));
define ('CRLF', chr(13) . chr(10));
define ('QUOT', chr(34));
define ('TIC', chr(39));

// define ('CHR_NULL', chr(0));
define ('CHR_TAB', chr (9));
define ('CHR_WSP', chr (32));
define ('CHR_BSLASH', chr (92));

define ('TIME', time());

// PHP settings
if (SYSTEM_TIME_LIMIT)
set_time_limit (SYSTEM_TIME_LIMIT);
session_cache_expire(SYSTEM_SESSION_CACHE_EXPIRE);
error_reporting (E_ALL);
date_default_timezone_set (SYSTEM_TIMEZONE);
ini_set ('display_errors', SYSTEM_DISPLAY_ERRORS);
ini_set ('log_errors', SYSTEM_LOG_ERRORS);
ini_set ('error_log', SYSTEM_LOG_FILE);
ini_set('session.use_strict_mode', 0);

spl_autoload_register (function ($class_name)
{ // magic autoload
global $aliases, $components, $io;

$parts = explode ('_', $class_name);

@list ($prefix, $component) = explode ('_', $class_name, 3);

$ob_length = ob_get_length();

if (isset ($aliases[$component]) and isset ($components[$aliases[$component]]))
{ // include from component
$path = PATH_COMPONENTS . $aliases[$component] . '/' . $components[$aliases[$component]] . '/' . $component . '/' . $class_name . '.php';
if (is_file ($path))
{ // file found
include $path;
if (ob_get_length() > $ob_length)
print 'vazamento de caracteres em ' . $class_name . '<br>';
return;
} // file found

if (count ($parts) > 2)
{ // try subdirectory
$path = PATH_COMPONENTS . $aliases[$component] . '/' . $components[$aliases[$component]] . '/' . $component . '/' . $parts[2] . '/' . $class_name . '.php';
if (is_file ($path))
{ // file found
include $path;
if (ob_get_length() > $ob_length)
print 'vazamento de caracteres em ' . $class_name . '<br>';
return;
} // file found
} // try subdirectory
} // include from component

if (is_file (PATH_ENGINE . $prefix . '/' . $class_name . '.php'))
include PATH_ENGINE . $prefix . '/' . $class_name . '.php';
elseif (count ($parts) >= 3 and is_file (PATH_LIBRARY . $parts[1] . '/' . $parts[2] . '/' . $class_name . '.php'))
include PATH_LIBRARY . $parts[1] . '/' . $parts[2] . '/' . $class_name . '.php';
elseif (is_file (PATH_LIBRARY . $component . '/' . $class_name . '.php'))
include PATH_LIBRARY . $component . '/' . $class_name . '.php';
elseif (count ($parts) == 4 and is_file (PATH_LIBRARY . $parts[1] . '/' . $parts[2] . '/' . $class_name . '.php'))
include PATH_LIBRARY . $parts[1] . '/' . $parts[2] . '/' . $class_name . '.php';
elseif ($prefix == $class_name or $prefix == 'eclClass')
{ // not found
if (isset ($io->log))
$io->log->addMessage ($class_name . ' not found', 'autoload');
eval ('class ' . $class_name . '{ public $is_phantom = true; }');
} // not found
else
{ // not found
if (isset ($io->log))
$io->log->addMessage ($class_name . ' not found', 'autoload');
eval ('class ' . $class_name . ' extends eclClass_' . strtolower( substr ($prefix, 3)) . '{ public $is_phantom = true; }');
} // not found

// Arquivos de classes nao podem vazar caracteres para o buffer de saida
if (ob_get_length() > $ob_length)
print 'vazamento de caracteres em ' . $class_name . '<br>';
} // magic autoload
);

function print_a ($array) { print nl2br (str_replace (['&', QUOT, '<'], ['&amp;', '&quot;', '&lt;'], eclIo_file::array2string ($array))); }

/*
The packager will look for the lines starting with "!".
Do not change these lines!
*/

//!packager:start_of_files
//!start_of_packager_settings

define('SYSTEM_IS_PACKED', false);
define('SYSTEM_DATA_FILE_EXTENSION', '.json');

//!end_of_packager_settings
//!packager:end_of_files

// Input and output drivers
$io = new eclEngine_io ();

// Data managers
$store = new eclEngine_store();

// Applications tre
$system = new eclEngine_application ();

// The document
$document = new eclEngine_document ();
$document->route($io->request);
$document->sessionStart ($document->application->ignoreSession ());
$document->dispatch();
$document->render();

if (!$document->application->ignoreSession ())
$io->session->save();

$store->close();
$io->close();

$io->request->giveBack ($document);

__halt_compiler();