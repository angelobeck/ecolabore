<?php

class eclApp_userHome_public
{ // class eclApp_userHome_public

static function is_child ($me, $name)
{ // function is_child
if ($name == '-public')
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

$me->data = $store->control->read ('userHome_public_content');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application->parent;

$data = &$store->userContent->open ($me->userId, '-index');

$formulary = $document->createFormulary ('userHome_public_edit', $data, 'public');

if ($formulary->command ('cancel'))
{ // close pop up
$document->dataReplace ('layouts/dialog_close');
} // close pop up

if ($formulary->command ('save') and $formulary->save ())
{ // save
if (!$data)
{ // creates index page
$data = $formulary->data;
$data['name'] = '-index';
$data['mode'] = MODE_DOMAIN;
$data['parent_id'] = 0;
$store->userContent->insert ($me->userId, $data);
} // creates index page
else
$data = $formulary->data;
$document->mod->humperstilshen->alert ('userHome_personal_alertDataUpdated');
} // save

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_userHome_public

?>