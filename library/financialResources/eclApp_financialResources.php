<?php

class eclApp_financialResources
{ // class eclApp_financialResources

static function is_child ($me, $name)
{ // function is_child
if ($name == 'resources')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('resources');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->map = array ('financialAccount', 'financialAccount_create');
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_FOLDER, 0, '-resources');
if ($me->data)
{ // data exists
$me->id = $me->data['id'];
return;
} // data exists

$data = $store->control->read ('financialResources_preset');
$data['mode'] = MODE_FOLDER;
$data['marker'] = 52;
$data['name'] = '-resources';
$me->id = $store->domainContent->insert ($me->domainId, $data);
$me->data = $data;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;
$view = true;

// action edit
if ($document->actions ('resources', 'edit'))
$view = self::action_edit ($document);

// context edit
$document->mod->context->appendChild ('financialResources_edit')
->active ($document->actions ('resources', 'edit'))
->url (true, true, '_resources-edit');

// context new account
$document->mod->context->appendChild ('financialAccount_create_contextNew')
->appendFolder ('-new-account');

if (!$view)
return;

$document->mod->list = new eclMod_financialResources_list ($document);
} // function dispatch

static function action_edit ($document)
{ // function action_edit
$me = $document->application;

$formulary = $document->createFormulary ('financialResources_edit', $me->data, 'resources');
$formulary->action = '_resources-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['resources']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['resources']);
$me->data = $formulary->data;
$document->dataReplace ($me->data);
return true;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->dataReplace ('financialResources_edit');
return false;
} // function action_edit

} // class eclApp_financialResources

?>