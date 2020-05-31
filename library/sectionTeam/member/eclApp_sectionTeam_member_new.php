<?php

class eclApp_sectionTeam_member_new
{ // class eclApp_sectionTeam_member_new

static function is_child ($me, $name)
{ // function is_child
if ($name == '-new-member')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-new-member');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionTeam_member_new');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('sectionTeam_member_new');
if ($formulary->command ('cancel'))
{ // cancel
$document->dataReplace ('layouts/dialog_close');
return;
} // cancel

if ($formulary->command ('save') and $formulary->save ())
{ // save
$user['name'] = $formulary->data['name'];
$user['password'] = $formulary->data['password'];
$user['status'] = 1;
$userId = $store->user->insert ($user);
$data = $formulary->data;
$data['mode'] = MODE_DOMAIN;
$data['parent_id'] = 0;
$data['name'] = '-register';
$store->userContent->insert ($userId, $data);

$data = $formulary->data;
$name = $data['name'];
$data['name'] = '-user-' . $name;
$data['mode'] = MODE_SUBSCRIPTION;
$data['master_id'] = 0;
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

} // class eclApp_sectionTeam_member_new

?>