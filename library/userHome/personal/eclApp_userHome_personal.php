<?php

class eclApp_userHome_personal
{ // class eclApp_userHome_personal

static function is_child ($me, $name)
{ // function is_child
if ($name == '-personal')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-personal');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;

$me->data = $store->control->read ('userHome_personal_content');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application->parent;

$data = &$store->userContent->open ($me->userId, '-register');

$formulary = $document->createFormulary ('userHome_personal_edit', $data, 'personal');

if ($formulary->command ('cancel'))
{ // close pop up
$document->dataReplace ('layouts/dialog_cancel');
} // close pop up

if ($formulary->command ('save') and $formulary->save ())
{ // save
if (!$data)
{ // create a register
$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['parent_id'] = 0;
$data['name'] = '-register';
$data['encrypt'] = 1;
$store->userContent->insert ($me->userId, $data);
} // create a register
else
{ // update
$data = $formulary->data;
$data['encrypt'] = 1;
} // update

if (isset ($data['local']['document']['cpf']))
{ // update cpf
$user = &$store->user->open ($document->user->name);
$user['document'] = str_replace ('-', '', $data['local']['document']['cpf']);
} // update cpf

$document->mod->humperstilshen->alert ('userHome_personal_alertDataUpdated');
} // save

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_userHome_personal

?>