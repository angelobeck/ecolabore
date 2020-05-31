<?php

class eclApp_financialIncoming
{ // class eclApp_financialIncoming

static function is_child ($me, $name)
{ // function is_child
if ($name == 'incoming')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('incoming');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_FOLDER, 0, '-incoming');
if ($me->data)
{ // data exists
$me->id = $me->data['id'];
return;
} // data exists

$data = $store->control->read ('financialIncoming_preset');
$data['mode'] = MODE_FOLDER;
$data['marker'] = 52;
$data['name'] = '-incoming';
$me->id = $store->domainContent->insert ($me->domainId, $data);
$me->data = $data;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;
$view = true;

// action edit
if ($document->actions ('incoming', 'edit'))
$view = self::action_edit ($document);

// context edit
$document->mod->context->appendChild ('financialIncoming_edit')
->active ($document->actions ('incoming', 'edit'))
->url (true, true, '_incoming-edit');

if (!$view)
return;

$document->mod->list = new eclMod_financialIncoming_list ($document);
} // function dispatch

static function action_edit ($document)
{ // function action_edit
$me = $document->application;

$formulary = $document->createFormulary ('financialIncoming_edit', $me->data, 'incoming');
$formulary->action = '_incoming-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['incoming']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['incoming']);
$me->data = $formulary->data;
$document->dataReplace ($me->data);
return true;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->dataReplace ('financialIncoming_edit');
return false;
} // function action_edit

} // class eclApp_financialIncoming

?>