<?php

$startTime = microtime(true);
ob_start();

define('SYSTEM_CONFIG_FILE', 'config.php');
!is_file(__DIR__ . '/' . SYSTEM_CONFIG_FILE) or include __DIR__ . '/' . SYSTEM_CONFIG_FILE;

defined('FOLDER_APPLICATIONS') or define('FOLDER_APPLICATIONS', '');
defined('FOLDER_DATABASE') or define('FOLDER_DATABASE', 'database/');
defined('FOLDER_ENGINE') or define('FOLDER_ENGINE', 'ecolabore-engine/');
defined('FOLDER_SITES') or define('FOLDER_SITES', 'sites/');
defined('FOLDER_USERS') or define('FOLDER_USERS', 'users/');

define('PATH_ROOT', __DIR__ . '/');
define('PATH_APPLICATIONS', __DIR__ . '/' . FOLDER_APPLICATIONS);
define('PATH_DATABASE', __DIR__ . '/' . FOLDER_DATABASE);
define('PATH_ENGINE', __DIR__ . '/' . FOLDER_ENGINE);
define('PATH_SITES', __DIR__ . '/' . FOLDER_SITES);
define('PATH_USERS', __DIR__ . '/' . FOLDER_USERS);

defined('SYSTEM_HOSTING_MODE') or define('SYSTEM_HOSTING_MODE', 'subfolders'); // 'single' | 'subfolders' | 'subdomains'
defined('SYSTEM_HOST') or define('SYSTEM_HOST', 'localhost');
defined('SYSTEM_DEFAULT_DOMAIN_NAME') or define('SYSTEM_DEFAULT_DOMAIN_NAME', 'admin');
defined('SYSTEM_ADMIN_URI') or define('SYSTEM_ADMIN_URI', 'admin');
defined('SYSTEM_USERS_URI') or define('SYSTEM_USERS_URI', 'friends');
defined('SYSTEM_DEFAULT_LANGUAGE') or define('SYSTEM_DEFAULT_LANGUAGE', 'pt');
defined('SYSTEM_DEFAULT_CHARSET') or define('SYSTEM_DEFAULT_CHARSET', 'ISO-8859-1');
defined('SYSTEM_SESSION_TTL') or define('SYSTEM_SESSION_TTL', 3600);
defined('SYSTEM_SESSION_CACHE_EXPIRE') or define('SYSTEM_SESSION_CACHE_EXPIRE', 300);
defined('SYSTEM_TIME_LIMIT') or define('SYSTEM_TIME_LIMIT', 6);
defined('SYSTEM_TIMEZONE') or define('SYSTEM_TIMEZONE', 'America/Sao_Paulo');
defined('SYSTEM_DISPLAY_ERRORS') or define('SYSTEM_DISPLAY_ERRORS', 1);
defined('SYSTEM_LOG_ERRORS') or define('SYSTEM_LOG_ERRORS', 0);
defined('SYSTEM_HTTPS_REDIRECT') or define('SYSTEM_HTTPS_REDIRECT', false);
defined('SYSTEM_REWRITE_ENGINE') or define('SYSTEM_REWRITE_ENGINE', false);

defined('DATABASE_ENABLE') or define('DATABASE_ENABLE', true);
defined('DATABASE_CLIENT') or define('DATABASE_CLIENT', 'sqlite');
defined('DATABASE_DB') or define('DATABASE_DB', 'ecolabore');
defined('DATABASE_HOST') or define('DATABASE_HOST', '');
defined('DATABASE_USER') or define('DATABASE_USER', 'root');
defined('DATABASE_PASSWORD') or define('DATABASE_PASSWORD', '');
defined('DATABASE_LOG_FILE') or define('DATABASE_LOG_FILE', 'database_log');
defined('DATABASE_PREFIX') or define('DATABASE_PREFIX', 'ecl_');
defined('DATABASE_ENCRYPT_ENABLE') or define('DATABASE_ENCRYPT_ENABLE', false);
defined('DATABASE_ENCRYPT_CIPHER') or define('DATABASE_ENCRYPT_CIPHER', 'aes-128-cbc');
defined('DATABASE_ENCRYPT_KEY') or define('DATABASE_ENCRYPT_KEY', '');
defined('DATABASE_ENCRYPT_HASH') or define('DATABASE_ENCRYPT_HASH', 'fE7QxHKV');

define('SYSTEM_SCRIPT_PATH', __FILE__);
define('SYSTEM_SCRIPT_NAME', substr(__FILE__, 1 + strlen(__DIR__)));
define('SYSTEM_COMPILER_HALT_OFFSET', __COMPILER_HALT_OFFSET__);
define('SYSTEM_LOG_FILE', __DIR__ . '/.log_php_errors');

defined('CHR_FNS') or define('CHR_FNS', '+');
define('CRLF', chr(13) . chr(10));
define('TIME', time());

// PHP settings
if (SYSTEM_TIME_LIMIT)
    set_time_limit(SYSTEM_TIME_LIMIT);
session_cache_expire(SYSTEM_SESSION_CACHE_EXPIRE);
error_reporting(E_ALL);
date_default_timezone_set(SYSTEM_TIMEZONE);
ini_set('display_errors', SYSTEM_DISPLAY_ERRORS);
ini_set('log_errors', SYSTEM_LOG_ERRORS);
ini_set('error_log', SYSTEM_LOG_FILE);
ini_set('session.use_strict_mode', 0);

$applicationsMaps = [];
$applicationsPaths = [];

function setMap(string $applicationHost, string $applicationChild, string $applicationPath = ''): void
{
    global $applicationsMaps, $applicationsPaths;
    if (!isset($applicationsMaps[$applicationHost])) {
        $applicationsMaps[$applicationHost] = [$applicationChild];
    } else {
        $applicationsMaps[$applicationHost][] = $applicationChild;
    }
    if ($applicationPath !== '') {
        $applicationsPaths[$applicationChild] = $applicationPath;
    }
}

function getMap(string $applicationHost): array
{
    global $applicationsMaps;
    if (isset($applicationsMaps[$applicationHost]))
        return $applicationsMaps[$applicationHost];

    return [];
}

$includePaths = [PATH_ENGINE];
foreach (scandir(PATH_APPLICATIONS) as $folder) {
    if ($folder[0] == '.')
        continue;
    if (!is_dir(PATH_APPLICATIONS . $folder))
        continue;
    if (!is_dir(PATH_APPLICATIONS . $folder . '/components'))
        continue;
    if (!is_file(PATH_APPLICATIONS . $folder . '/map.php'))
        continue;

    $includePaths[] = PATH_APPLICATIONS . $folder . '/';
    includeFile(PATH_APPLICATIONS . $folder . '/map.php');
}

spl_autoload_register(
    function ($className) {
        global $includePaths;
        if (substr($className, 0, 3) != "ecl") {
            return;
        }

        $parts = explode('_', $className);
        $partsCount = count($parts);

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

$page->sessionStart();
$page->route();
$page->dispatch();
$page->render();

$store->close();
$io->close();

header_remove('Content-Type');
header('Content-Type: ' . $page->contentType);

if (isset($page->flags['html'])) {
    print '<!DOCTYPE html><html><body>';
    print nl2br(eclIo_convert::htmlSpecialChars($page->buffer));
    print '</body></html>';
} else {
    if (isset($page->actions['export'])) {
        file_put_contents($page->domain->name . '.html', $page->buffer);
    }
    print $page->buffer;
}

ob_end_flush();

__halt_compiler();              