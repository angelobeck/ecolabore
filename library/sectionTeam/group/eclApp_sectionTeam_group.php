<?php

class eclApp_sectionTeam_group
{ // class eclApp_sectionTeam_group

static function is_child ($me, $name)
{ // function is_child
global $store;
$data = $store->domainContent->openChild ($me->domainId, MODE_GROUP, 0, $name);
if ($data)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = &$store->domainContent->open ($me->domainId, $me->name);
$me->id = $me->data['id'];
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$view = true;

// Action remove
if ($document->actions ('group', 'remove'))
return self::action_remove ($document);

// action edit
if ($document->actions ('group', 'edit'))
$view = self::action_edit ($document);

// context edit
$document->mod->context->appendChild ('sectionTeam_group_edit')
->active ($document->actions ('group', 'edit'))
->url (true, true, '_group-edit');

// Context remove
$document->mod->context->appendChild ('sectionTeam_group_remove')
->url (true, true, '_group-remove')
->confirm ('sectionTeam_group_removeConfirm');

if ($view)
{ // view
$document->mod->editor->enable ();
$document->mod->list = new eclMod_sectionTeam_group_listMembers ($document);
} // view
} // function dispatch

static function action_edit ($document)
{ // function action_edit
$me = $document->application;

$formulary = $document->createFormulary ('sectionTeam_group_edit', $me->data, 'group_edit');
$formulary->action = '_group-edit';

if ($formulary->command ('cancel'))
{ // cancel
unset ($document->actions['group']);
return true;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
unset ($document->actions['group']);
$me->data = $formulary->data;
$document->dataReplace ($me->data);
return true;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
return false;
} // function action_edit

static function action_remove ($document)
{ // function action_remove
global $store;
$me = $document->application;
unset ($document->actions['group']);

$parent = $me->parent;

$store->domainContent->delete ($me->domainId, $me->id);
$store->domainFile->deletePrefixedFiles ($me->domainId, $me->name);

$parent->reset ();
// reindex brothers
$store->domainContent->childrenReindex ($parent->domainId, MODE_group, $parent->id);

$document->application = $parent;
$document->reload = $document->url ();
$document->application->dispatch ($document);
} // function action_remove

static function remove ($me)
{ // function remove
global $store;

/*
* Do not remove by parent
*/
} // function remove

} // class eclApp_sectionTeam_group

?>