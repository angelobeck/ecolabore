<?php

class eclApp_userHome_phone
{ // class eclApp_userHome_phone

static function is_child ($me, $name)
{ // function is_child
if (INTEGRATION_SMS_ENABLE and $name == '-phone')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (INTEGRATION_SMS_ENABLE)
return array ('-phone');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userHome_phone_content');
$me->isDomain = true;
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application->parent;

$user = $store->user->open ($document->user->name);

if ($document->actions ('phone', 'input'))
return self::action_phone_input ($document);

if (isset($document->session[$me->name]['userHome_phone_code']))
return self::action_phone_check ($document);

if ($user['phone'] == '')
return self::action_phone_input ($document);

self::action_phone_verified ($document);
} // function dispatch

static function action_phone_input ($document)
{ // function action_phone_input
global $io, $store;
$me = $document->application->parent;

$data = [];

$register = &$store->userContent->open ($me->userId, '-register');
if (isset($register['local']['phone']['country']) and isset ($register['local']['phone']['area']) and isset ($register['local']['phone']['number']))
$data['phone'] = $register['local']['phone']['country'] . '-' . $register['local']['phone']['area'] . '-' . $register['local']['phone']['number'];

$formulary = $document->createFormulary ('userHome_phone_input', $data, 'input');
$formulary->action = '_phone-input';

if ($formulary->command ('next') and $formulary->save ())
{ // save
$code = mt_rand (1000, 9999);
$phone = str_replace (['-', ' ', '(', ')'], '', $formulary->data['phone']);

$document->session[$me->name]['userHome_phone_code'] = $code;
$document->session[$me->name]['userHome_phone_number'] = $phone;
$io->sms->send (array (
'number' => $phone, 
'message' => strval ($code)
));

return self::action_phone_check ($document);
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function action_phone_input

static function action_phone_check ($document)
{ // function action_phone_check
global $store;
$me = $document->user;

$formulary = $document->createFormulary ('userHome_phone_check', [], 'check');

if ($formulary->command ('next') and $formulary->save ())
{ // save
if ($formulary->data['code'] == $document->session[$me->name]['userHome_phone_code'])
{ // verified
$phone = $document->session[$me->name]['userHome_phone_number'];
preg_match('/^([+]55)([0-9]{2})([0-9]+)$/', $phone, $parts);

$user = &$store->user->open ($me->name);
$register = &$store->userContent->open ($me->userId, '-register');

$user['phone'] = str_replace (['+', '-'], '', $phone);
$register['local']['phone'] = [
'country' => '+55',
'area' => $parts[2],
'number' => $parts[3]
];

unset ($document->session[$me->name]['userHome_phone_code']);
unset ($document->session[$me->name]['userHome_phone_number']);
return self::action_phone_verified ($document);
} // verified

$document->mod->humperstilshen->alert ('userHome_phone_alertInvalidCode');
} // save

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ('userHome_phone_alertInvalidCode');
$document->dataMerge ('userHome_phone_contentCheck');
} // function action_phone_check

static function action_phone_verified ($document)
{ // function action_phone_verified
global $store;
$me = $document->user;

$formulary = $document->createFormulary ('userHome_phone_verified', [], 'verified');

if ($formulary->command ('cancel'))
return $document->dataReplace ('layouts/dialog_cancel');

if ($formulary->command ('update'))
return self::action_phone_input ($document);

$document->mod->formulary = $formulary;
$document->dataReplace ('userHome_phone_verified');

$register = $store->userContent->open ($me->userId, '-register');
$document->data['number'] = $register['local']['phone']['area'] . ' - ' . $register['local']['phone']['number'];
} // function action_phone_verified

} // class eclApp_userHome_phone

?>