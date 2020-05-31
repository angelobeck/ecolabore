<?php

class eclApp_sectionTeam_member_import
{ // class eclApp_sectionTeam_member_import

static function is_child ($me, $name)
{ // function is_child
if ($name == '-import-profile')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-import-profile');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionTeam_member_import');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('sectionTeam_member_import');
if ($formulary->command ('cancel'))
{ // cancel
$document->dataReplace ('layouts/dialog_close');
return;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
$name = $data['name'];
$userId = $store->user->getId ($name);

$data['name'] = '-user-' . $name;
$data['mode'] = MODE_SUBSCRIPTION;
$data['marker'] = $me->parent->data['marker'];
$data['owner_id'] = $userId;
$store->domainContent->insert ($me->domainId, $data);

$document->dataReplace ('layouts/dialog_close');
return;
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_sectionTeam_member_import

?>