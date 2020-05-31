<?php

class eclApp_systemInstallation_index
{ // class eclApp_systemInstallation_index

static function is_child ($parent, $name)
{ // function is_child
if ($name == '')
return true;
if ($name == '-default')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($parent)
{ // function get_children_names
return array ('');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
// As subpastas deverão ser ignoradas
$me->ignoreSubfolders = true;
$me->data = $store->control->read ('systemInstallation_index');
unset ($me->pathway[1]);
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

if (!is_file (PATH_ROOT . SYSTEM_CONFIG_FILE))
{ // config file not found
$n = @file_put_contents (PATH_ROOT . SYSTEM_CONFIG_FILE, '<' . '?php');
if (!$n)
{ // unable to write
$document->dataMerge ('systemInstallation_accessDenied');
return;
} // unable to write
unlink (PATH_ROOT . SYSTEM_CONFIG_FILE);
} // config file not found

if (!isset ($document->session['systemInstallation']))
{ // presets
$document->session['systemInstallation'] = $store->control->read ('systemInstallation_templateForConfig');
if ($document->protocol == 'https')
$document->session['systemInstallation']['SYSTEM_HTTPS_REDIRECT'] = true;
if ($document->rewriteEngine or strpos (' ' . strtolower ($_SERVER['SERVER_SOFTWARE']), 'apache'))
$document->session['systemInstallation']['SYSTEM_REWRITE_ENGINE'] = true;
} // presets
if (!isset ($document->session['systemInstallation_page']))
$document->session['systemInstallation_page'] = 0;

PROCESS_PAGE:
$page = $document->session['systemInstallation_page'];

$formulary = $document->createFormulary ('systemInstallation_' . $page, $document->session['systemInstallation'], 'page' . $page);
if (!$document->rewriteEngine and strpos (' ' . strtolower ($_SERVER['SERVER_SOFTWARE']), 'apache'))
$formulary->flags['rewrite_engine_choose'] = true;

if ($formulary->command ('next') and $formulary->save ())
{ // next
$document->session['systemInstallation'] = $formulary->data;

$document->session['systemInstallation_page']++;
if ($document->session['systemInstallation_page'] == 3)
return self::finish_installation ($document);

goto PROCESS_PAGE;
} // next
elseif ($formulary->command ('previous'))
{ // previous
$formulary->save ();
$document->session['systemInstallation'] = $formulary->data;
$document->session['systemInstallation_page']--;
goto PROCESS_PAGE;
} // previous

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->data['url'] = $document->url ();
$document->data['folder'] = PATH_ROOT;
} // function dispatch

static function finish_installation ($document)
{ // function finish_installation
$constants = $document->session['systemInstallation'];
if ($constants['SYSTEM_HOSTING_MODE'] == 0)
{ // set default configuration
$constants['DATABASE_ENABLE'] = true;
$constants['DATABASE_CLIENT'] = 'sqlite';
if (openssl_cipher_iv_length ('AES-128-CBC'))
{ // enable encryption
$constants['DATABASE_ENCRYPT_ENABLE'] = true;
$constants['DATABASE_ENCRYPT_CIPHER'] = 'AES-128-CBC';
$constants['DATABASE_ENCRYPT_KEY'] = base64_encode (openssl_random_pseudo_bytes(32));
$constants['DATABASE_ENCRYPT_HASH'] = base64_encode (openssl_random_pseudo_bytes(32));
} // enable encryption

$constants['SYSTEM_DEFAULT_DOMAIN_NAME'] = 'ecolabore';
$constants['SYSTEM_DEFAULT_LANGUAGE'] = $document->lang;
} // set default configuration

ksort ($constants);

$buffer = '<' . '?php' . CRLF . CRLF;
foreach ($constants as $key => $value)
{ // each constant
$buffer .= "define (" . TIC . $key . TIC . ", ";
if ($value === false)
$buffer .= 'false';
elseif ($value === true)
$buffer .= 'true';
elseif (is_int ($value))
$buffer .= strval ($value);
elseif (is_string ($value))
$buffer .= "" . TIC . str_replace (TIC, CHR_BSLASH . TIC, $value) . TIC . "";

$buffer .= ");" . CRLF;
} // each constant
$buffer .= CRLF . '?>';

header ('Content-type: application/force-download');
header ('Content-Disposition: attachment; filename="config.php"');
print $buffer;

$document->session = [];
exit;
} // function finish_installation

} // class eclApp_systemInstallation_index

?>