<?php

class eclApp_userHome_welcome
{ // class eclApp_userHome_welcome

static function is_child ($me, $name)
{ // function is_child
if ($name == '-welcome')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store, $system;
$me = $document->user;
if (isset ($me->data['local']['gender']) and $document->user->data['local']['gender'] == 'female')
$gender = 'F';
else
$gender = 'M';

if ($me->name == ADMIN_IDENTIFIER)
$status = STATUS_ADMIN;
else
$status = $store->user->getStatus ($me->name);

if ($status == STATUS_REMOVED and !$document->access (4, $system->groups))
{ // disabled
$document->session = array ('session_time' => TIME);
$document->mod->humperstilshen->alert ('userHome_welcome_disabled' . $gender);
} // disabled
else
{ // welcome
if ($document->pathway[0] == SYSTEM_PROFILES_URI)
$data = $store->control->read ('userHome_welcome_welcomeToProfile');
else
$data = $store->control->read ('userHome_welcome_welcome');

$data['local']['last_login'] = $me->data['local']['last_login'] ?? '';
if ($me->userId)
$me->data['local']['last_login'] = TIME;

$document->mod->humperstilshen->alert ($data);
} // welcome
} // function dispatch

} // class eclApp_userHome_welcome

?>