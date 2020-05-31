<?php

class eclIo_request
{ // class eclIo_request

public $protocol;
public $beta = '';
public $host;
public $pathway;
public $lang = false;
public $actions = [];
public $received = [];
public $uploaded = [];
public $rewriteEngine = false;

private $headers = [];
private $headersRemove = [];
private $headersRemoveAll = false;

public function __construct ()
{ // function __construct
global $io, $store;

// Protocol
if (isset ($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on')
$this->protocol = 'https';
else
$this->protocol = 'http';

// Beta
if (isset ($_GET['beta']))
$this->beta = 'beta.';

// Rewrite Engine
if (isset ($_GET['RewriteEngine']))
$this->rewriteEngine = true;
elseif (SYSTEM_REWRITE_ENGINE and strpos (' ' . strtolower ($_SERVER['SERVER_SOFTWARE']), 'apache') and !is_file (PATH_ROOT . '.htaccess'))
$io->htaccess->regenerate ();

if (defined ('SYSTEM_EXTERNAL_REDIRECT'))
{ // external redirect
$uri = SYSTEM_EXTERNAL_REDIRECT;
$host = SYSTEM_HOST;
} // external redirect
else
{ // get uri

// Root dir
$root = substr ($_SERVER['SCRIPT_NAME'], 1,  - strlen (SYSTEM_SCRIPT_NAME));

// host
$host = $_SERVER['SERVER_NAME'];

// uri
$uri = substr ($_SERVER['REQUEST_URI'], 1 + strlen ($root));

if ($pos = strpos ($uri, '='))
$uri = substr ($uri, $pos + 1);
elseif (substr ($uri, 0, strlen (SYSTEM_SCRIPT_NAME)) == SYSTEM_SCRIPT_NAME)
$uri = substr ($uri, strlen (SYSTEM_SCRIPT_NAME));

} // get uri

// pathway
$pathway = [];
foreach (explode ('/', $uri) as $dir)
{ // each dir
if (preg_match ('%^[a-zA-Z0-9._+-]+$%', $dir))
$pathway[] = $dir;
} // each dir

// Actions
if (substr (end ($pathway), 0, 1) == '_')
{ // actions
$actions_groups = explode ('_', substr (array_pop ($pathway), 1));
foreach ($actions_groups as $action_group)
{ // each action group
// uma ação pode ter argumentos separados por hifem "-"
$action_parts = explode ('-', $action_group);
$this->actions[$action_parts[0]] = $action_parts;
} // each action group
} // actions

// Reconheceremos por ventura algum idioma
if ($this->isLanguage (end ($pathway)))
$this->lang = array_pop ($pathway);
reset ($pathway);

// set reference mode
if (SYSTEM_HOSTING_MODE == 2)
{ // working in Portal mode
$mainHostLength = strlen (SYSTEM_HOST);
if (substr ($host, -$mainHostLength) != SYSTEM_HOST)
{ // process aliase
$parts = explode ('.', $host);
if ($parts[0] == 'beta')
$this->beta = array_shift ($parts) . '.';
if ($parts[0] == 'www')
array_shift ($parts);
$aliase = implode ('.', $parts);
$domain = $store->domain->openByAliase ($aliase);
if (isset ($domain['name']))
array_unshift ($pathway, $domain['name']);
else
array_unshift ($pathway, SYSTEM_ADMIN_URI);

$this->host = $aliase . '/';
$this->pathway = $pathway;
} // process aliase
else
{ // regular host
$v_host = substr ($host, 0,  - $mainHostLength);
$parts = explode ('.', $v_host);
if ($parts and $parts[0] == 'beta')
$this->beta = array_shift ($parts) . '.';
elseif ($parts and $parts[0] == 'www')
array_shift ($parts);
if ($parts and strlen ($parts[0]))
array_unshift ($pathway, $parts[0]);
else
array_unshift ($pathway, SYSTEM_DEFAULT_DOMAIN_NAME);
$this->host = SYSTEM_HOST . '/';
$this->pathway = $pathway;
} // regular host
} // working in Portal mode
elseif (SYSTEM_HOSTING_MODE == 1)
{ // subdomains in subfolders
// Domínio padrão caso não haja uma pasta
if (!$pathway)
$pathway[0] = SYSTEM_DEFAULT_DOMAIN_NAME;
elseif ($pathway[0] == '-' . SYSTEM_ADMIN_URI or $pathway[0] == '-' . SYSTEM_PROFILES_URI)
$pathway[0] = substr ($pathway[0], 1);

$this->host = $host . '/' . $root;
$this->pathway = $pathway;
} // subdomains in subfolders
else
{ // single mode
if ($pathway and ($pathway[0] == '-' . SYSTEM_ADMIN_URI or $pathway[0] == '-' . SYSTEM_PROFILES_URI))
$pathway[0] = substr ($pathway[0], 1);
else
array_unshift ($pathway, SYSTEM_DEFAULT_DOMAIN_NAME);

$this->host = $host . '/' . $root;
$this->pathway = $pathway;
} // single mode

if (!get_magic_quotes_gpc ())
$this->received = $_POST;
else
{ // unescape string - magic quotes off
$received = [];
foreach ($_POST as $key => $value)
{ // each field
$received[$key] = str_replace (array ('\\\\', '\\\'', '\\"'), array ('\\', '\'', QUOT), $value);
} // each field
$this->received = $received;
} // unescape string -- magic quotes off

foreach ($_FILES as $name => $entry)
{ // each uploaded file
if (is_string ($entry['name']))
{ // single file
$this->uploaded[$name][0] = $entry;
continue;
} // single file

$count = count ($entry['name']);
for ($i = 0; $i < $count; $i++)
{ // loop index
foreach ($entry as $key => $array)
{ // each key
$this->uploaded[$name][$i][$key] = $array[$i];
} // each key
} // loop index
} // each uploaded file
} // function __construct

public function isLanguage ($lang)
{ // function isLanguage
global $store;

if (strlen ($lang) != 2)
return false;
if ($lang == 'pt' or $lang == 'en' or $lang == 'es' or $lang == 'it' or $lang == 'fr')
return true;

if ($store->control->read ('labels/lang/' . $lang))
return true;

return false;
} // function isLanguage

public function header ($header)
{ // function header
$this->headers[] = $header;
} // function header

public function headerRemove ($header=false)
{ // function headerRemove
if ($header === false)
$this->headersRemoveAll = true;
else
$this->headersRemove[] = $header;
} // function headerRemove

public function giveBack ($document)
{ // function giveBack
global $io;

if (isset ($this->actions['html']))
{ // output page source
print nl2br (str_replace (array ('&', '<', QUOT), array ('&amp;', '&lt;', '&quot;'), $document->buffer));
if (!$io->log->silent)
print nl2br (str_replace (array ('&', '<', QUOT), array ('&amp;', '&lt;', '&quot;'), $io->buffer));
} // output page source
else
{ // normal output
print $document->buffer;
if (!$io->log->silent)
print $io->buffer;
} // normal output

if ($this->headersRemoveAll)
header_remove ();
elseif ($this->headersRemove)
{ // clear headers
foreach ($this->headersRemove as $header)
{ // clear each header
header_remove ($header);
} // clear each header
} // clear headers

header ('HTTP/1.1 200 OK');
header ('X-Powered-By: ECOLABORE/' . ECOLABORE_DATA['local']['version']);
header ("Cache-Control: no-cache, must-revalidate, max-age=0");
header ('Content-Type: text/html; charset=' . strtolower ($document->charset));

if (isset ($document->data['content_security_policy']))
header ("Content-Security-Policy: " . $document->data['content_security_policy']);

header ('Content-length: ' . strval (ob_get_length ()));

if (isset ($document->application->data['flags']['modLayout_cacheable']))
{ // cacheable
header_remove ('Pragma');
header_remove ('Expires');
header ('Cache-Control: public, only-if-cached, max-age=' . $document->application->data['flags']['modLayout_cacheable']);
} // cacheable

foreach ($this->headers as $header)
{ // each header
header ($header);
} // each header

header ('Connection: close');

ob_end_flush ();
} // function giveBack

public function close ()
{ // function close
} // function close

} // class eclIo_request

?>