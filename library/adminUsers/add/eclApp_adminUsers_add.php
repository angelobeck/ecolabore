<?php

class eclApp_adminUsers_add
{ // class eclApp_adminUsers_add

static function is_child ($me, $name)
{ // function is_child
global $store;

if ($name == '-new-user')
return true;
if (!$store->user->getId ($name) and is_dir (PATH_PROFILES . $name))
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-new-user');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('adminUsers_add_content');
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;

$data = [];

if ($document->application->name != '-new-user' and $document->application->name != '-default')
$data['name'] = $document->application->name;

$formulary = $document->createFormulary ('adminUsers_add_edit', $data, 'useradd');

if ($formulary->command ('save') and $formulary->save ())
{ // formulary saved

$user['name'] = $formulary->data['name'];
$user['password'] = $formulary->data['password'];
$user['status'] = 1;
$userId = $store->user->insert ($user);
$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['parent_id'] = 0;
$data['name'] = '-register';
$store->userContent->insert ($userId, $data);
$document->received = [];
unset ($document->actions['user']);
$parent = $document->application->parent;
unset ($document->application);
$parent->reset ();
$document->application = $parent->child ($formulary->data['name']);
$document->application->dispatch ($document);
$document->reload = $document->url ();
return;
} // formulary saved
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_adminUsers_add

?>