<?php

class eclEngine_document
{ // class eclEngine_document

// O preenchimento destas propriedades determinará a geração do documento:
public $mode = SYSTEM_HOSTING_MODE;
public $protocol = 'http'; // string => the protocol used in the comunication
public $host = 'localhost/'; // string => the main host
public $pathway = ['-blank']; // array => the pathway to route the application
public $lang = false; // string | false => the language or auto define
public $actions = []; // array => the actions from url
public $received = []; // array => the posted data from formularies
public $session = []; // array => the session data $io->session->cache
private $sid = 0; // the session id
private $cookie = true; // bool => If user agent accepts cookies
public $rewriteEngine = false; // bool => if the Apache Rewrite Engine is enabled

// Após o início da seção e do roteamento, estas propriedades estarão definidas:
public $user; // object eclEngine_application => the user connected
public $domain; // object eclEngine_application => the domain of this application
public $subscription; // object eclEngine_application => the relationcheep between the user and the domain
public $application; // object eclEngine_application => the application to be dispatched

// Estas propriedades são preenchidas em __construct() com objetos auxiliares:
public $render; // object eclEngine_render => the renderizer engine
public $mod; // object eclEngine_mod => a loader for modules

// Estas propriedades auxiliam no processamento da renderização:
public $defaultLang; // string => the preferred language if $lang is not available
public $charset; // string => the charset
public $data = []; // array is the main content of the document
public $buffer = ''; // string => the output document to be sent
public $ids = []; // array ids used in HTML id property to prevent duplicated identifiers
public $templateEditable = false; // bool true if the user can edit template modules
public $contentEditable = false; // bool true if the online visual editor is enable
public $protectedLayout = true; // to hide email addresses from spiders
public $printableLayout = false; // bool true advises modules that the user wish to print the document
public $reload = false; // false or an url string to redirect the browser
public $preload = []; // array eclEngine_application => an array with the applications to be dispatched before the main application

public function __construct ()
{ // function __construct
global $system;

$this->render = new eclEngine_render ($this);
$this->mod = new eclEngine_mod ($this);

$this->user = $system->child (SYSTEM_PROFILES_URI)->child ('-anonimous');
$this->subscription = $system->child (SYSTEM_PROFILES_URI)->child ('-anonimous');
} // function __construct

public function __clone ()
{ // function __clone
$this->actions = []; // array => the actions from url
$this->received = []; // array => the posted data from formularies
unset ($this->session);
$this->session = []; // array => the session data $io->session->cache

$this->mod = new eclEngine_mod ($this);
$this->render = new eclEngine_render ($this);

$this->data = []; // array is the main content of the document
$this->buffer = ''; // string => the output document to be sent
$this->ids = []; // array ids used in HTML id property to prevent duplicated identifiers
$this->templateEditable = false;
$this->contentEditable = false;
$this->printableLayout = false;
$this->reload = false;
$this->preload = []; // array eclEngine_application => an array with the applications to be dispatched before the main application
} // function __clone

public function route ($request=false)
{ // function route
global $system;

if (is_object ($request))
{ // get request params
$this->protocol = $request->protocol;
$this->host = $request->host;
$this->pathway = $request->pathway;
$this->lang = $request->lang;
$this->actions = $request->actions;
$this->received = $request->received;
$this->rewriteEngine = $request->rewriteEngine;
} // get request params

// Vamos procurar pelo nome do domínio
$pathway = $this->pathway;
$domainName = array_shift ($pathway);
$this->domain = $system->child ($domainName);

// Se o domínio não existir, vamos forçar a aplicação systemDefault
if (!is_object ($this->domain))
$this->domain = $system->child ('-default');

if (!count ($pathway))
$pathway = [''];

// Vamos rotear as subpastas chamando outra função
if ($this->domain->ignoreSubfolders == false)
$this->application = $this->routeSubfolders ($this->domain, $pathway);
else
$this->application = $this->domain;

// Se neste ponto não tivermos encontrado uma aplicação, novamente procuramos por uma aplicação padrão
if (!is_object ($this->application))
{ // force app_system_error
$this->domain = $system->child ('-default');
$this->application = $this->domain;
} // force app_system_error
} // function route

private function routeSubfolders ($me, $pathway)
{ // function routeSubfolders
$application = false;
$folder = array_shift ($pathway);
$application = $me->child ($folder);

// a aplica??o foi encontrada
if (is_object ($application))
{ // route children

// Se a aplica??o ignorar subpastas, retornamos esta aplica??o
if ($application->ignoreSubfolders)
return $application;

// Se n?o houverem mais subpastas, retornamos esta aplica??o
if (!count ($pathway))
return $application;

// Se ainda n?o retornamos, rotearemos a pr?xima subpasta
$application = $this->routeSubfolders ($application, $pathway);

// Se o roteamento encontrou uma aplica??o, retornaremos daqui
if (is_object ($application))
return $application;
} // route children

// Se o filho n?o existir, procuramos uma aplica??o que responda por padr?o
$application = $me->child ('-default');

// Se n?o existir uma aplica??o padr?o, retornamos daqui
if ($application === false)
return false;

// Se a aplica??o ignorar subpastas, retornamos esta aplica??o
if ($application->ignoreSubfolders)
return $application;

// Se n?o houverem mais subpastas, retornamos esta aplica??o
if (!count ($pathway))
return $application;

// Se ainda n?o retornamos, rotearemos a pr?xima subpasta
return $this->routeSubfolders ($application, $pathway);
} // function routeSubfolders

public function sessionStart ($ignore=false)
{ // function sessionStart
global $io, $store, $system;

if ($ignore)
return;

$this->session = &$io->session->cache;

if (!defined ('ADMIN_IDENTIFIER') or !defined ('ADMIN_PASSWORD'))
return;

if (isset ($this->actions['logout']))
{ // logout
$this->session = [];
unset ($this->actions['logout']);
return;
} // logout

if (isset ($this->actions['login']))
{ // login
$instance = isset ($this->actions['login'][1]) ? $this->actions['login'][1] : '1';
$name = isset ($this->received['login' . $instance . 'fields_name']) ? strtolower ($this->received['login' . $instance . 'fields_name']) : '';
$password = isset ($this->received['login' . $instance . 'fields_password']) ? strtolower ($this->received['login' . $instance . 'fields_password']) : '';

$user = [];
if ($name == ADMIN_IDENTIFIER)
{ // admin
$user['name'] = ADMIN_IDENTIFIER;
$user['id'] = ADMIN_IDENTIFIER;
$user['password'] = ADMIN_PASSWORD;
} // admin
elseif (preg_match ('/^[+][0-9][0-9-]+$/', $name))
{ // select phone
$where['phone'] = str_replace (['+', '-'], '', $name);
$rows = $io->database->select ($store->user, $where);
if (count ($rows) == 1)
$user = $rows[0];
} // select phone
elseif (preg_match ('/^[0-9]{9}[-]?[0-9]{2}$/', $name))
{ // select document
$where['document'] = str_replace ('-', '', $name);
$rows = $io->database->select ($store->user, $where);
if (count ($rows) >= 1)
$user = $rows[0];
} // select document
elseif (preg_match ('/^[a-z0-9_-]+$/', $name))
{ // open by name
$user = $store->user->open ($name);
} // open by name
elseif (preg_match ('/^[a-zA-Z0-9._-]+[@][a-zA-Z0-9._-]+$/', $name))
{ // select mail
$where['mail'] = $name;
$rows = $io->database->select ($store->user, $where);
if (count ($rows) >= 1)
$user = $rows[0];
} // select mail

if (isset ($user['password'][0]) and eclIo_database::passwordCheck ($password, $user['password']))
{ // login pass true
$this->session['user_id'] = $user['id'];
$this->session['user_name'] = $user['name'];
// $io->session->regenerate ();
} // login pass true
else
{ // invalid login
$this->mod->humperstilshen->loginError = true;
} // invalid login
} // login
elseif (isset ($this->session['time']) and $this->session['time'] + SYSTEM_SESSION_TTL < TIME)
{ // session expired
$this->session = [];
} // session expired

if (isset ($this->session['user_name'][0]))
{ // connected
$this->user = $system->child (SYSTEM_PROFILES_URI)->child ($this->session['user_name']);

if ($subscription = $store->domainContent->getUserSubscription ($this))
$this->subscription = $subscription;

if (!isset ($this->session['time']))
{ // first load
$this->session['time'] = TIME;
$this->preload[] = $this->user->child ('-welcome');
} // first load
else
{ // else load
$this->session['time'] = TIME;
} // else load
} // connected

} // function sessionStart

public function dispatch ()
{ // function dispatch
global $system;

// Antes de despachar a aplica??o, definiremos o idioma

if (isset ($this->domain->data['flags']['default_lang']))
$this->defaultLang = $this->domain->data['flags']['default_lang'];
elseif (defined ('SYSTEM_DEFAULT_LANGUAGE'))
$this->defaultLang = SYSTEM_DEFAULT_LANGUAGE;
else
$this->defaultLang = 'en';

if ($this->lang == false)
$this->lang = $this->defaultLang;

// Definiremos o conjunto de caract?res
if (isset ($this->application->flags['default_charset']))
$this->charset = $this->application->flags['default_charset'];
else
$this->charset = SYSTEM_DEFAULT_CHARSET;

// Layout para impress?o
if ($this->actions ('layout', 'print'))
$this->printableLayout = true;

// Caso o acesso seja restrito, desviaremos o controle para outra aplica??o
if (!$this->access ($this->application->access))
{ // access denied
$application = $system->child ('-access-denied');
$application->pathway = $this->application->pathway;
$application->groups = $this->application->groups;
$application->access = $this->application->access;
$application->parent = $this->application->parent;
$this->application = $application;
} // access denied
else
{ // normal access

// busca por aplica??es de alerta
if (isset ($this->actions['preload']) and count ($this->actions['preload']) > 1)
{ // preload application
array_shift ($this->actions['preload']);
$preload = $system->child ('-preload');
foreach ($this->actions['preload'] as $name)
{ // loop names
$preload = $preload->child ($name);
if ($preload === false)
break;
if ($preload->ignoreSubfolders)
break;
} // loop names

if ($preload !== false)
$this->preload[] = $preload;
} // preload application

// rodaremos aplica??es preliminares
foreach ($this->preload as $application)
{ // each preload application
$application->dispatch ($this);
} // each preload application
} // normal access

// Finalmente, despacharemos a aplica??o
$this->application->dispatch ($this);
} // function dispatch

public function render ()
{ // function render
global $io, $store, $system;

if (SYSTEM_HTTPS_REDIRECT and $this->protocol != 'https')
$this->reload = $this->url (true, true, true, 'https');

if ($this->reload)
{ // reload
header ('Location: ' . $this->reload);
// $this->buffer = '<html><script> location.replace(' . QUOT . $this->reload . QUOT . ') </script></html>';
return;
} // reload

if ($this->buffer != '')
return;

// Idioma e codifica??o
$this->data['lang'] = $this->lang;
$this->data['charset'] = $this->charset;

// Data da gera??o do documento
if (isset ($this->application->data['updated']))
$this->data['date'] = date ('c', $this->application->data['updated']);
else
$this->data['date'] = date ('c');

// Moeda corrente
if (isset ($this->data['flags']['currency']))
$this->data['currency'] = $this->data['flags']['currency'];
else
$this->data['currency'] = SYSTEM_DEFAULT_CURRENCY;

// Nome e c?digo da moeda corrente
$currency = $store->control->read ('labels/currency/' . $this->data['currency']);
if (isset ($currency['text']['caption']))
$this->data['currency_caption'] = $currency['text']['caption'];
else
$this->data['currency_caption'] = $this->textMerge ($this->data['currency']);
if (isset ($currency['local']['symbol']))
$this->data['currency_symbol'] = $currency['local']['symbol'];
else
$this->data['currency_symbol'] = $this->data['currency'];

// Security policy
$safeUrls = $this->domain->data['flags']['contentSecurityPolicy'] ?? SYSTEM_CONTENT_SECURITY_POLICY;
$this->data['content_security_policy'] = "default-src " . TIC . "self" . TIC . " " . TIC . "unsafe-inline" . TIC . " data: " . $safeUrls . "; object-src " . TIC . "none" . TIC;

// Folha de estilo
if (isset ($this->data['flags']['modLayout_sharedStyle']))
{ // set shared references
$this->data['url_styles'] = $this->urlFiles ($this->data['flags']['modLayout_sharedStyle'], true, '-shared');
$this->data['url_scripts'] = $this->urlFiles ($this->data['flags']['modLayout_sharedScript'], true, '-shared');
} // set shared references
else
{ // define paths
$template = $store->domainExtras->openChild ($this->domain->domainId, MODE_TEMPLATE, 0, 'templates/default');

if (isset ($template['updated']))
{ // updated
$date = TIME;
$this->data['url_styles'] = $this->url ([$this->domain->name, '-styles', 'ecolabore-generated-template-' . $date . '.css'], false);
$this->data['url_icons'] = $this->url ([$this->domain->name, '-icons', 'ecolabore-icons-' . $date . '.svg'], false);
$this->data['url_scripts'] = $this->url ([$this->domain->name, '-scripts', 'ecolabore-scripts-' . $date . '.js'], false);
} // updated
else
{ // static
$this->data['url_styles'] = $this->url ([$this->domain->name, '-styles', 'ecolabore-generated-template.css'], false);
$this->data['url_icons'] = $this->url ([$this->domain->name, '-icons', 'ecolabore-icons.svg'], false);
$this->data['url_scripts'] = $this->url ([$this->domain->name, '-scripts', 'ecolabore-scripts.js'], false);
} // static
} // define paths
$this->data['url_scripts'] = $this->url ([$this->domain->name, '-shared', 'libraries', 'ecolabore-basics.js'], false);

// Flags de acesso
if ($this->access (1))
{ // user is connected
$this->data['user_is_connected'] = 1;
if ($this->access (2))
{ // user is subscribed
$this->data['user_is_subscribed'] = 1;
if ($this->access (3))
{ // user is member
$this->data['user_is_member'] = 1;
if ($this->access (4))
$this->data['user_is_admin'] = 1;
} // user is member
} // user is subscribed
} // user is connected

if ($this->contentEditable)
$this->data['editor_is_enabled'] = 1;

// Renderiza o documento
if ($this->printableLayout)
$documentBase = $store->control->read ('base/print');
elseif (isset ($this->data['flags']['modLayout_base']) and $store->control->read ('base/' . $this->data['flags']['modLayout_base']))
$documentBase = $store->control->read ('base/' . $this->data['flags']['modLayout_base']);
else
$documentBase = $store->control->read ('base/responsive');

if (!isset ($documentBase['html']))
exit ('Template not found or not valid');
if (isset ($documentBase['parsed']))
$this->render->render_tags_level ($documentBase['parsed']);
else
$this->render->render_tags ($documentBase['html']);

// Cola os recortes
$this->renderCuts ();

// Recolhe o documento pronto
$this->buffer = $this->render->buffer;
} // function render

private function renderCuts ()
{ // function renderCuts
$render = $this->render;
if (isset ($render->cuts['style']))

$render->cuts['style'] = '<style>' . CRLF . $render->cuts['style'] . '</style>' . CRLF;

if (isset ($render->cuts['script']) or isset ($render->cuts['footerscript']))
{ // script
if (isset ($render->cuts['script']))
$render->cuts['script'] = '<script>' . CRLF . $render->cuts['script'];
else
$render->cuts['script'] = '<script>' . CRLF;

if (isset ($render->cuts['footerscript']))
$render->cuts['script'] .= $render->cuts['footerscript'];

$render->cuts['script'] .= '</script>' . CRLF;
} // script

for ($index = $render->pasteIndex; $index; $index--)
{ // loop cuts
if (isset ($render->cuts[$render->pasteNames[$index]]))
$render->buffer = substr_replace ($render->buffer, $render->cuts[$render->pasteNames[$index]], $render->pastePointer[$index], 0);
} // loop cuts
} // function renderCuts

public function access ($level, $groups=false)
{ // function access
if (!$level)
return true;

if (!defined ('ADMIN_IDENTIFIER'))
return false;

if (!$this->user->userId and $this->user->name != ADMIN_IDENTIFIER)
return false;

if ($level == 1)
return true;

if ($level == 2 and $this->subscription->domainId)
return true;

if ($groups === false)
$groups = $this->application->groups;

foreach ($groups as $group)
{ // walk groups list
$status = $group->check ($this);
if ($status >= $level)
return true;
} // walk groups list

return false;
} // function access

public function actions ()
{ // function actions
$args = func_get_args ();
if (!isset ($this->actions[$args[0]]))
return false;

$actions = $this->actions[$args[0]];
foreach ($args as $arg)
{ // each argument
if ($arg != array_shift ($actions))
return false;
} // each argument
return true;
} // function actions

public function createFormulary ($controlName=false, $data=false, $prefix='edit')
{ // function createFormulary
return new eclEngine_formulary ($this, $controlName, $data, $prefix);
} // function createFormulary

public function createListItem ($data=false, $local=[])
{ // function createListItem
return new eclEngine_listItem ($this, $data, $local);
} // function createListItem

public function createMail ()
{ // function createMail
return new eclIo_smtp ();
} // function createMail

public function createModule ($name, $arguments=[])
{ // function createModule
if (!preg_match ('/^[a-z][a-z0-9_]*$/', $name))
$name = 'default';

$mod = new eclEngine_module ($this);
$this->mod->$name->setModule ($mod, $arguments);

if (isset ($mod->data['local']['name']))
$name = $mod->data['local']['name'];

if (isset ($this->ids[$name]))
{ // more instances
$this->ids[$name]++;
$mod->data['local']['name'] = $name . '_' . $this->ids[$name];
} // more instances
else
{ // first instance
$this->ids[$name] = 1;
$mod->data['local']['name'] = $name;
} // first instance

return $mod;
} // function createModule

public function dataMerge ($data)
{ // function dataMerge
global $store;
if (is_string ($data))
$data = $store->control->read ($data);
foreach ($data as $key => $value)
{ // each value
switch ($key)
{ // switch key
case 'text':
case 'flags':
case 'local':
if (!is_array ($value))
break;
foreach ($value as $field => $content)
{ // each field
$this->data[$key][$field] = $content;
} // each field
break;

default:
$this->data[$key] = $value;
} // switch key
} // each value
} // function dataMerge

public function dataReplace ($data)
{ // function dataReplace
global $store;
if (is_string ($data))
$data = $store->control->read ($data);
$this->data = array_replace_recursive (['flags' => $this->application->flags ()], $data);
} // function dataReplace

public function textMerge ()
{ // function textMerge
$buffer = '';
foreach (func_get_args () as $field)
{ // each field
if (is_string ($field))
$buffer .= $field;
elseif (is_array ($field))
{ // select language
$content = $this->textSelectLanguage ($field);
$buffer .= $content[TEXT_CONTENT];
} // select language
elseif (is_int ($field))
$buffer .= strval ($field);
} // each field

$result[$this->lang] = [
TEXT_CONTENT => $buffer,
TEXT_HTML => 2
];
if ($this->charset == 'ISO-8859-1')
$result[$this->lang][TEXT_CHARSET] = 1;

return $result;
} // function textMerge

public function textRender ($field)
{ // function textRender
$render = $this->render;
$buffer = $render->buffer;
$render->buffer = '';
$render->render ($this->textSelectLanguage ($field));
$result = $render->buffer;
$render->buffer = $buffer;
return $result;
} // function textRender

public function textSelectLanguage ($field)
{ // function textSelectLanguage
static $cs = [0 => 'UTF-8', 1 => 'ISO-8859-1'];

// Se n?o houver conte?do
if (!is_array ($field) or !$field)
return [1 => '', 3 => $this->lang];

// Vamos procurar o conte?do no idioma do documento
if (isset ($field[$this->lang]))
{ // found lang
$content = $field[$this->lang];
$content[TEXT_LANG] = $this->lang;
} // found lang
elseif (isset ($field[$this->defaultLang]))
{ // default lang
$content = $field[$this->defaultLang];
$content[TEXT_LANG] = $this->defaultLang;
} // default lang
else
{ // next language
$lang = key ($field);
$content = current ($field);
$content[TEXT_LANG] = $lang;
} // next language

// Se n?o for especificada uma codifica??o
if (!isset ($content[TEXT_CHARSET]))
$content[TEXT_CHARSET] = 0; // UTF-8

// Se o conte?do estiver em uma codifica??o diferente da do documento
if (isset ($cs[$content[TEXT_CHARSET]]) and $cs[$content[TEXT_CHARSET]] != $this->charset)
$content[TEXT_CONTENT] = mb_convert_encoding ($content[TEXT_CONTENT], $this->charset, $cs[$content[TEXT_CHARSET]]);

return $content;
} // function textSelectLanguage

public function url ($pathway=true, $lang=true, $actions=true, $protocol=true)
{ // function url
if ($protocol === true)
$protocol = $this->protocol . '://';
elseif ($protocol === false)
$protocol = 'http://';
else
$protocol .= '://';

// Pathway
if ($pathway === true)
$pathway = $this->application->pathway;
elseif (!is_array ($pathway) or !$pathway)
$pathway = [$this->domain->name];

// Lang
if ($lang === true)
$lang = $this->lang;
if ($lang !== false and $lang != $this->defaultLang)
$pathway[] = $lang;

// Actions
if ($actions !== false)
{ // actions
if ($this->sid)
{ // add sid
if (is_string ($actions))
$actions .= '_sid-' . $this->sid;
else
$actions = '_sid-' . $this->sid;
} // add sid
if (is_string ($actions))
$pathway[] = $actions;
} // actions

if ($this->mode == 0)
{ // hide default domain name
if ($pathway[0] == SYSTEM_DEFAULT_DOMAIN_NAME or $pathway[0] == '-default')
array_shift ($pathway);
else
$pathway[0] = '-' . $pathway[0];
} // hide default domain name
elseif ($this->mode == 2)
{ // virtual hosts enabled
$domain = array_shift ($pathway);
if ($domain == SYSTEM_DEFAULT_DOMAIN_NAME)
$url = $protocol . SYSTEM_HOST . '/';
// elseif ($aliase = $store->domain->getAliase ($domain))
// $url = $protocol . $aliase . '/';
else
$url = $protocol . $domain . '.' . SYSTEM_HOST . '/';

if (!$this->rewriteEngine)
{ // add script name
if (!$pathway)
{ // empty
if (SYSTEM_SCRIPT_NAME == 'index.php')
return $url;
else
return $url . SYSTEM_SCRIPT_NAME;
} // empty

$url .= SYSTEM_SCRIPT_NAME . '?url=';
} // add script name

if ($pathway)
$url .= implode ('/', $pathway);

return $url;
} // virtual hosts enabled

$url = $protocol . $this->host;

if (!$this->rewriteEngine)
{ // add script name
if (!$pathway)
{ // empty
if (SYSTEM_SCRIPT_NAME == 'index.php')
return $url;
else
return $url . SYSTEM_SCRIPT_NAME;
} // empty

$url .= SYSTEM_SCRIPT_NAME . '?url=';
} // add script name

$url .= implode ('/', $pathway);

return $url;
} // function url

public function urlFiles ($file, $domain=true, $special='-files')
{ // function urlFiles
if (is_bool ($domain))
$domain = $this->domain->name;

if ($this->mode == 2)
{ // virtual hosts enabled
if ($domain == SYSTEM_DEFAULT_DOMAIN_NAME)
$url = $this->protocol . '://' . SYSTEM_HOST . '/';
// elseif ($aliase = $store->domain->getAliase ($domain))
// $url = $this->protocol . '://' . $aliase . '/';
else
$url = $this->protocol . '://' . $domain . '.' . SYSTEM_HOST . '/';

if (!$this->rewriteEngine)
$url .= SYSTEM_SCRIPT_NAME . '?url=';

$url .= $special . '/' . $file;
return $url;
} // virtual hosts enabled

$url = $this->protocol . '://' . $this->host;

if (!$this->rewriteEngine)
$url .= SYSTEM_SCRIPT_NAME . '?url=';

if ($this->mode == 0)
return $url . $special . '/' . $file;

return $url . $domain . '/' . $special . '/' . $file;
} // function urlFiles

} // class eclEngine_document

?>