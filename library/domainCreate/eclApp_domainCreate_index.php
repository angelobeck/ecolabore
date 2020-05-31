<?php

class eclApp_domainCreate_index
{ // class eclApp_domainCreate_index

static function is_child ($me, $name)
{ // function is_child
global $io, $store;
if ($name == '' or $name == '-default')
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
$me->data = $store->control->read ('domainCreate_content');
array_pop ($me->pathway);
$me->ignoreSubfolders = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store, $system;
$me = $document->application;

$document->data['url'] = $document->url (true, false, false);
$document->data['enable_user_domains'] = SYSTEM_ENABLE_USER_DOMAINS;
$document->data['enable_user_subscriptions'] = SYSTEM_ENABLE_USER_SUBSCRIPTIONS;

if (!SYSTEM_ENABLE_USER_DOMAINS)
return;

if (!$document->access (1))
return;

$data['local'] = $document->user->data['local'];

$formulary = $document->createFormulary ('domainCreate_edit', $data, 'domaincreate');

if ($formulary->command ('save') and $formulary->save ())
{ // save
$domain['name'] = $document->domain->name;
$domain['status'] = 1;
mkdir (PATH_DOMAINS . $domain['name']);

$domainId = $store->domain->insert ($domain);
$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['name'] = '-register';
$store->domainContent->insert ($domainId, $data);

$group = &$store->domainGroup->open ($domainId, 1);
$group[$document->user->userId] = 4;

$document->dataReplace ([]);
$system->reset ();
$document->domain = $system->child ($domain['name']);
$document->application = $document->domain->child ('');
$document->application->dispatch ($document);
return;
} // save

$document->dataReplace ('domainCreate_contentCreate');
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_domainCreate_index

?>