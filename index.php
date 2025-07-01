<?php

$startTime = microtime(true);
ob_start();

defined('SERVER_HOSTING_MODE') or define('SERVER_HOSTING_MODE', 'single'); // 'single', 'subfolders', 'subdomains'
defined('SERVER_PROTOCOL') or define('SERVER_PROTOCOL', 'http:'); // 'http:', 'https:'
defined('SERVER_HOST') or define('SERVER_HOST', 'localhost');
defined('SERVER_REWRITE_ENGINE') or define('SERVER_REWRITE_ENGINE', false);
defined('SERVER_SCRIPT_NAME') or define('SERVER_SCRIPT_NAME', substr(__FILE__, 1 + strlen(__DIR__)));
defined('SERVER_CONFIG_FILE') or define('SERVER_CONFIG_FILE', __DIR__ . '/config.php');
defined('SERVER_DISPLAY_ERRORS') or define('SERVER_DISPLAY_ERRORS', true);
defined('SERVER_LOG_ERRORS') or define('SERVER_LOG_ERRORS', false);
defined('SERVER_LOG_FILE') or define('SERVER_LOG_FILE', __DIR__ . '/php_log.txt');
defined('SERVER_DATABASE_LOG_FILE') or define('SERVER_DATABASE_LOG_FILE', __DIR__ . '/database_log.txt');
defined('SERVER_TIME_LIMIT') or define('SERVER_TIME_LIMIT', 3);

defined('PATH_ROOT') or define('PATH_ROOT', __DIR__ . '/');
defined('PATH_APPLICATION') or define('PATH_APPLICATION', __DIR__ . '/');
defined('PATH_ENGINE') or define('PATH_ENGINE', __DIR__ . '/');

defined('MODULES') or define('MODULES', []);

if (is_file(SERVER_CONFIG_FILE))
    include SERVER_CONFIG_FILE;

    defined('ADMIN_NAME') or define('ADMIN_NAME', 'admin');
defined('ADMIN_TITLE') or define('ADMIN_TITLE', 'Administrator');
defined('ADMIN_GENDER') or define('ADMIN_GENDER', 'male');
defined('ADMIN_PASSWORD') or define('ADMIN_PASSWORD', '');
defined('ADMIN_HELPERS') or define('ADMIN_HELPERS', '');

defined('APPLICATION_ADMIN_NAME') or define('APPLICATION_ADMIN_NAME', 'admin');
defined('APPLICATION_USERS_NAME') or define('APPLICATION_USERS_NAME', 'friends');

defined('DATABASE_ENABLED') or define('DATABASE_ENABLED', false);
defined('DATABASE_CLIENT') or define('DATABASE_CLIENT', 'sqlite');
defined('DATABASE_HOST') or define('DATABASE_HOST', '');
defined('DATABASE_USER') or define('DATABASE_USER', 'root');
defined('DATABASE_PASSWORD') or define('DATABASE_PASSWORD', '');
defined('DATABASE_DB') or define('DATABASE_DB', 'ecolabore');
defined('DATABASE_PREFIX') or define('DATABASE_PREFIX', 'ecl_');

defined('DEFAULT_CHARSET') or define('DEFAULT_CHARSET', 'UTF-8');
defined('DEFAULT_DOMAIN_NAME') or define('DEFAULT_DOMAIN_NAME', 'ecolabore');
defined('DEFAULT_LANGUAGE') or define('DEFAULT_LANGUAGE', 'pt');
defined('DEFAULT_TIMEZONE') or define('DEFAULT_TIMEZONE', 'america/sao_paulo');
defined('DEFAULT_SESSION_TTL') or define('DEFAULT_SESSION_TTL', '3600');

defined('ENCRYPTION_ENABLED') or define('ENCRYPTION_ENABLED', false);
defined('ENCRYPTION_CYPHER') or define('ENCRYPTION_CYPHER', '');
defined('ENCRYPTION_KEY') or define('ENCRYPTION_KEY', '');
defined('ENCRYPTION_HASH') or define('ENCRYPTION_HASH', '');

defined('FOLDER_ACETS') or define('FOLDER_ACETS', 'acets/');
defined('FOLDER_CACHE') or define('FOLDER_CACHE', 'cache/');
defined('FOLDER_DATABASE') or define('FOLDER_DATABASE', 'database/');
defined('FOLDER_SITES') or define('FOLDER_SITES', 'sites/');
defined('FOLDER_USERS') or define('FOLDER_USERS', 'users/');

define('PATH_ACETS', PATH_ROOT . FOLDER_ACETS);
define('PATH_CACHE', PATH_ROOT . FOLDER_CACHE);
define('PATH_DATABASE', PATH_ROOT . FOLDER_DATABASE);
define('PATH_SITES', PATH_ROOT . FOLDER_SITES);
define('PATH_USERS', PATH_ROOT . FOLDER_USERS);

define('PACK_COMPILER_HALT_OFFSET', __COMPILER_HALT_OFFSET__);
define('CRLF', chr(13) . chr(10));
define('TIME', time());

// PHP settings
if (SERVER_TIME_LIMIT)
    set_time_limit(SERVER_TIME_LIMIT);
session_cache_expire(180);
error_reporting(E_ALL);
date_default_timezone_set(DEFAULT_TIMEZONE);
ini_set('display_errors', SERVER_DISPLAY_ERRORS);
ini_set('log_errors', SERVER_LOG_ERRORS);
ini_set('error_log', SERVER_LOG_FILE);
ini_set('session.use_strict_mode', 0);

$applicationsMaps = [];

function setMap(string $applicationHost, string $applicationChild): void
{
    global $applicationsMaps;
    if (!isset($applicationsMaps[$applicationHost])) {
        $applicationsMaps[$applicationHost] = [$applicationChild];
    } else {
        $applicationsMaps[$applicationHost][] = $applicationChild;
    }
}

function getMap(string $applicationHost): array
{
    global $applicationsMaps;
    if (isset($applicationsMaps[$applicationHost]))
        return $applicationsMaps[$applicationHost];
    else
        return [];
}

$includePaths = [PATH_ENGINE, PATH_APPLICATION];

if (defined('PLUGINS')) {
    foreach (PLUGINS as $folderName) {
        $includePaths[] = PATH_ROOT . $pluginName . '/';
    }
}

foreach ($includePaths as $path) {
    if (is_file($path . 'map.php'))
        include $path . 'map.php';
}

spl_autoload_register(
    function ($className) {
        global $includePaths;
        if (substr($className, 0, 3) != "ecl") {
            return;
        }

        $parts = explode('_', $className);
        $partsCount = count($parts);

        if (!PACK_ENABLED) {
            if ($partsCount == 1) {
                if (is_file(PATH_ENGINE . 'kernel/classes/' . $className . '.php')) {
                    includeFile(PATH_ENGINE . 'kernel/classes/' . $className . '.php');
                }
                return;
            }

            $engineFolder = strtolower(substr($parts[0], 3));
            if (is_file(PATH_ENGINE . 'kernel/' . $engineFolder . '/' . $className . '.php')) {
                includeFile(PATH_ENGINE . 'kernel/' . $engineFolder . '/' . $className . '.php');
                return;
            }

            foreach ($includePaths as $path) {
                for ($i = 1; $i < $partsCount; $i++) {
                    $dir = implode('/', array_slice($parts, 1, $i));
                    $file = $path . '/components/' . $dir . '/' . $className . '.php';
                    if (is_file($file)) {
                        includeFile($file);
                        return;
                    }
                }
            }
        }
        eval ('class ' . $className . ' extends ' . $parts[0] . '{ public $is_phantom = true; }');
    }
);

function includeFile(string $path): void
{
    $ob_length = ob_get_length();
    include $path;
    if (ob_get_length() > $ob_length)
        throw new Exception('Vazamento de caracteres em ' . $path, 6);
}

function print_data(mixed $data): void
{
    print nl2br(eclIo_convert::array2json($data));
}

// Next line indicates the scripts insertion point to the packager engine. Do not change it.
// __PACKAGER_INSERTION_POINT__ 

defined('PACK_ENABLED') or define('PACK_ENABLED', false);
defined('PACK_NAME') or define('PACK_NAME', 'ecolabore-engine');
defined('PACK_TIME') or define('PACK_TIME', '');
define('PACK_FILE', __FILE__);
define('PACK_HALT_COMPILER_OFFSET', __COMPILER_HALT_OFFSET__);

// Input and output drivers
$io = new eclEngine_io();

// Data managers
$store = new eclEngine_store();

// Applications tre
$applications = new eclEngine_application();

// The page
$page = new eclEngine_page();

$page->host = $io->request->host;
$page->path = $io->request->path;
$page->lang = $io->request->lang;
$page->actions = $io->request->actions;
$page->flags = $io->request->flags;
$page->received = $io->request->received;

$page->route();
$page->dispatch();
$page->render();

$store->close();
$io->close();

header_remove('Content-Type');
header('Content-Type: ' . $page->contentType);
header('Accept: */*');

if (isset($page->flags['html'])) {
    print '<!DOCTYPE html><html><body>';
    print nl2br(eclIo_convert::htmlSpecialChars($page->buffer));
    print '</body></html>';
} else {
    print $page->buffer;
}

ob_end_flush();

__halt_compiler();