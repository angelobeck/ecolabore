<?php

class eclApp_userJoin
{ // class eclApp_userJoin

static function is_child ($me, $name)
{ // function is_child
if ($name == '-join')
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-join');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userJoin_content');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$session = &$document->session;
if (!isset ($session['page']))
$document->session['page'] = 0;

do
{ // process pages
$process = 'page' . $document->session['page'];
} // process pages
while (self::$process ($document) != 'stop');
} // function dispatch

static function page0 ($document)
{ // function page0
global $store;

if ($domainId = $store->domain->getId (SYSTEM_DEFAULT_DOMAIN_NAME) and $id = $store->domainContent->findMarker ($domainId, 5))
{ // service terms found

$formulary = $document->createFormulary ('userJoin_page0', $document->session, 'page0');

if ($formulary->command ('next') and $formulary->save () and isset ($formulary->data['agree']) and !isset ($formulary->data['notAgree']))
{ // go page 1
$document->session = $formulary->data;
$document->session['page'] = 1;
return 'go next';
} // go page 1

$document->dataReplace ('userJoin_page0');
$document->mod->formulary = $formulary;
return 'stop';
} // service terms found

$document->session['page'] = 1;
return 'go next';
} // function page0

static function page1 ($document)
{ // function page1
global $store, $system;

if (isset ($document->session['agree']))
$formulary = $document->createFormulary ('userJoin_page1previous', $document->session, 'page1');
else
$formulary = $document->createFormulary ('userJoin_page1', $document->session, 'page1');

if ($formulary->command ('previous'))
{ // previous
$formulary->save ();
$document->session = $formulary->data;
$document->session['page'] = 0;
return 'go next';
} // previous

if ($formulary->command ('next') and $formulary->save ())
{ // next
$user['name'] = $formulary->data['name'];
$user['password'] = $formulary->data['password'];
$user['status'] = 1;
$userId = $store->user->insert ($user);

$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['name'] = '-register';
$data['encrypt'] = 1;
$store->userContent->insert ($userId, $data);

$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['name'] = '-index';
$store->userContent->insert ($userId, $data);

// login
$document->session['user_id'] = $userId;
$document->session['user_name'] = $user['name'];
$document->session['time'] = TIME;
$document->user = $system->child (SYSTEM_PROFILES_URI)->child ($user['name']);

$control = $store->control->read ('userJoin_alertWelcome');
$control['identifier'] = $user['name'];
$document->mod->humperstilshen->alert ($control);
$document->session['page'] = 2;
return 'go next';
} // next

$document->dataReplace ('userJoin_page1');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
return 'stop';
} // function page1

static function page2 ($document)
{ // function page2
global $store;

$formulary = $document->createFormulary ('userJoin_page2', [], 'page2');

if ($formulary->command ('cancel') and $formulary->save ())
{ // close
$document->dataReplace ('layouts/dialog_close');
return 'stop';
} // close

$document->dataReplace ('userJoin_page2');
$document->mod->list = new eclMod_userJoin_tools ($document);
$document->mod->formulary = $formulary;
return 'stop';
} // function page2

} // class eclApp_userJoin

?>