<?php

class eclApp_sectionTeam_group_new
{ // class eclApp_sectionTeam_group_new

static function is_child ($me, $name)
{ // function is_child
if ($name == '-new-group')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-new-group');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->access = 4;
$me->data = $store->control->read ('sectionTeam_group_new');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('sectionTeam_group_edit', [], 'new_group');
if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
$data['domain_id'] = $me->domainId;
$data['mode'] = MODE_GROUP;
$data['marker'] = $me->parent->data['marker'];
$store->domainContent->insert ($me->domainId, $data);
$parent = $me->parent;
$parent->reset ();
$document->application = $parent->child ($data['name']);
$document->application->dispatch ($document);
$document->reload = $document->url ();
return;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_sectionTeam_group_new

?>