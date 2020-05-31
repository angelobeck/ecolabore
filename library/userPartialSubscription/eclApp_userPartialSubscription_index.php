<?php

class eclApp_userPartialSubscription_index
{ // class eclApp_userPartialSubscription_index

static function is_child ($me, $name)
{ // function is_child
if ($name == '' or $name == '-index')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userPartialSubscription_content');
array_pop ($me->pathway);
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

if ($formulary->command ('next') and $formulary->save () and isset ($formulary->data['agree']))
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

$user = &$store->user->openById ($document->user->userId);
$user['name'] = $formulary->data['name'];
$user['password'] = $formulary->data['password'];
$user['status'] = 1;

$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['name'] = '-register';
$store->userContent->insert ($userId, $data);

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
$formulary = $document->createFormulary ('userJoin_page2', $document->user->data, 'page2');

if ($formulary->command ('cancel') and $formulary->save ())
{ // close
$data = &$store->userContent->open ($document->user->userId, '-register');
$data = $formulary->data;

$user =&$store->user->openById ($document->user->userId);

$document->application->name = $user['name'];
$document->session['user_name'] = $user['name'];
array_pop ($document->application->pathway);
$document->application->pathway[] = $user['name'];
$document->reload = $document->url();
return 'stop';
} // close

$document->dataReplace ('userJoin_page2');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
return 'stop';
} // function page2

} // class eclApp_userPartialSubscription_index

?>