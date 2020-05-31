<?php

class eclApp_sectionCart_order
{ // class eclApp_sectionCart_order

static function is_child ($me, $name)
{ // function is_child
global $store;

if ($name == '0')
return true;

$data = $store->domainComplement->openById ($me->domainId, intval ($name));
if ($data and $data['mode'] == MODE_ORDER)
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
$me->map = array ('financialAccount');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch

if (!$document->user->userId)
return self::action_please_connect ($document);

// Check order status
// ...

return self::action_choose_payment_method ($document);

} // function dispatch

static function action_please_connect ($document)
{ // function action_please_connect
$document->mod->panel->main = array ('content', 'login', 'user_subscribe');
$document->dataMerge ('sectionCart_contentPleaseConnect');
} // function action_please_connect

static function action_choose_payment_method ($document)
{ // function action_choose_payment_method
$formulary = $document->createFormulary ('sectionCart_order_confirmUserData', $document->user->data, 'user_data');
$formulary->pathway = $document->pathway;

if ($formulary->command ('previous'))
{ // previous
$document->application = $document->application->parent;
$document->pathway = $document->application->pathway;
$document->application->dispatch ($document);
return;
} // previous

if ($formulary->command ('next') and $formulary->save ())
{ // next
$me = $document->application->child ($formulary->data['account']);
if (!$me)
return;
$document->pathway[] = $me->name;
$me = $me->child ($formulary->data['method']);
if (!$me)
return;
$document->pathway[] = $me->name;
unset ($formulary->data['account'], $formulary->data['method']);
$document->user->data = $formulary->data;

$me->dispatch ($document);
return;
} // next

if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);

$document->mod->formulary = $formulary;
$document->dataMerge ('sectionCart_order_contentConfirmUserData');
} // function action_choose_payment_method

} // class eclApp_sectionCart_order

?>