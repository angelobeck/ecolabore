<?php

class eclApp_financialProjects
{ // class eclApp_financialProjects

static function is_child ($me, $name)
{ // function is_child
if ($name == 'projects')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('projects');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->map = array ('financialProjects_course');
$me->data = &$store->domainContent->openChild ($me->domainId, MODE_FOLDER, 0, '-projects');
if ($me->data)
{ // data exists
$me->id = $me->data['id'];
return;
} // data exists

$data = $store->control->read ('financialProjects_preset');
$data['mode'] = MODE_FOLDER;
$data['marker'] = 52;
$data['name'] = '-projects';
$me->id = $store->domainContent->insert ($me->domainId, $data);
$me->data = $data;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$me = $document->application;
$view = true;

// action edit
if ($document->actions ('projects', 'edit'))
$view = self::action_edit ($document);

// context edit
$document->mod->context->appendChild ('financialProjects_edit')
->active ($document->actions ('projects', 'edit'))
->url (true, true, '_projects-edit');

if (!$view)
return;

$document->mod->list = new eclMod_financialProjects_list ($document);
} // function dispatch

static function action_edit ($document)
{ // function action_edit
$me = $document->application;

$formulary = $document->createFormulary ('financialProjects_edit', $me->data, 'projects');
$formulary->action = '_projects-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['projects']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['projects']);
$me->data = $formulary->data;
$document->dataReplace ($me->data);
return true;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->dataReplace ('financialProjects_edit');
return false;
} // function action_edit

} // class eclApp_financialProjects

?>