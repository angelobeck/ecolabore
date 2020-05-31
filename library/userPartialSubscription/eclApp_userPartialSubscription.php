<?php

class eclApp_userPartialSubscription
{ // class eclApp_userPartialSubscription

static function is_child ($me, $name)
{ // function is_child
global $store;

if (substr ($name, 0, 4) != '-id-')
return false;

$id = intval (substr ($name, 4));
if (!$id)
return false;

$data = $store->user->openById ($id);
if (!$data)
return false;

return true;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->access = 4;
$me->map = array ('userPartialSubscription_index', 'userHome_welcome');
$user = $store->user->openById (intval (substr ($me->name, 4)));
if (isset ($user['mail'][0]))
$name = $user['mail'];
elseif (isset ($user['phone'][0]))
$name = '+' . $user['phone'];

$me->data['text']['caption'] = array ('en' => array (1 => $name));
$me->data['text']['title'] = array ('en' => array (1 => $name));

if (isset ($user['mail'][0]))
$me->data['local']['mail'] = $user['mail'];
if (isset ($user['phone'][0]))
$me->data['local']['mail'] = $user['phone'];

$me->userId = $user['id'];
$me->groups = array ( new eclGroup_user ($me->userId));
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->application = $document->application->child ('-index');
$document->application->dispatch ($document);
} // function dispatch

} // class eclApp_userPartialSubscription

?>