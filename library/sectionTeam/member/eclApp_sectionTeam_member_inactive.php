<?php

class eclApp_sectionTeam_member_inactive
{ // class eclApp_sectionTeam_member_inactive

static function is_child ($me, $name)
{ // function is_child
if ($name == '-inactive-member')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-inactive-member');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionTeam_member_inactive');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('sectionTeam_member_inactive');
if ($formulary->command ('cancel'))
{ // cancel
$document->dataReplace ('layouts/dialog_close');
return;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = &$store->domainContent->open ($me->domainId, $formulary->data['name']);
$data['parent_id'] = 0;
$data['master_id'] = 0;
$data['marker'] = $me->parent->data['marker'];

$document->dataReplace ('layouts/dialog_close');
return;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_sectionTeam_member_inactive

?>