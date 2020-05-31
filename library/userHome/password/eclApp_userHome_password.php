<?php

class eclApp_userHome_password
{ // class eclApp_userHome_password

static function is_child ($me, $name)
{ // function is_child
if ($name == '-password')
return true;

if ($name == '-password-update')
return true;

if ($name == '-recover-password')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-password');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;

if($me->name == '-password-update')
$me->data = $store->control->read ('userHome_password_contentUpdate');
elseif($me->name == '-recover-password')
$me->data = $store->control->read ('userHome_password_contentRecover');
else
$me->data = $store->control->read ('userHome_password_content');
$me->isDomain = true;
if ($me->name == '-recover-password')
$me->access = 0;
else
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;
$me = $document->application->parent;

if ($document->application->name == '-recover-password')
return self::action_recover_password ($document);

// dismiss change password alert
$me->data['flags']['userHome_passwordLastUpdate'] = TIME;

$formulary = $document->createFormulary ('userHome_password_edit', [], 'change');

if ($formulary->command ('cancel'))
{ // close pop up
return $document->dataReplace ('layouts/dialog_cancel');
} // close pop up

if ($formulary->command ('save') and $formulary->save ())
{ // change password
$user = &$store->user->open ($me->name);
$user['password'] = $formulary->data['password'];

if ($me->name == ADMIN_IDENTIFIER)
$io->systemConstants->set('ADMIN_PASSWORD', $io->database->password ($user['password']));

$document->mod->humperstilshen->alert ('userHome_password_alertPasswordChanged');
} // change password

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->formulary = $formulary;
} // function dispatch

static function action_recover_password ($document)
{ // function action_recover_password 
global $io, $store;
$me = $document->application->parent;
$document->user = $me;

if (!isset ($document->actions['token'][1]))
return $document->dataMerge ('userHome_password_contentRecoverInvalidToken');

if (!isset ($me->data['flags']['userRecoverPassword_token']) or !isset ($me->data['flags']['userRecoverPassword_time']))
return $document->dataMerge ('userHome_password_contentRecoverInvalidToken');

if ($document->actions['token'][1] != $me->data['flags']['userRecoverPassword_token'])
return $document->dataMerge ('userHome_password_contentRecoverInvalidToken');

if ($me->data['flags']['userRecoverPassword_time'] + SYSTEM_SESSION_TTL < TIME)
return $document->dataMerge ('userHome_password_contentRecoverInvalidToken');

$formulary = $document->createFormulary ('userHome_password_editRecoverPassword', [], 'recover');
$formulary->action = '_token-' . $document->actions['token'][1];

if ($formulary->command ('save') and $formulary->save ())
{ // change password
$user = &$store->user->open ($me->name);
$user['password'] = $formulary->data['password'];

if ($me->name == ADMIN_IDENTIFIER)
$io->systemConstants->set('ADMIN_PASSWORD', $io->database->password ($user['password']));

// dismiss change password alert
$me->data['flags']['userHome_passwordLastUpdate'] = TIME;

unset ($me->data['flags']['userRecoverPassword_token']);
unset ($me->data['flags']['userRecoverPassword_time']);
return $document->dataReplace ('userHome_password_contentRecoverSuccess');
} // change password

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->formulary = $formulary;
} // function action_recover_password 

} // class eclApp_userHome_password

?>