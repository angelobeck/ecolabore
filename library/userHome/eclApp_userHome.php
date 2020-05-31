<?php

class eclApp_userHome
{ // class eclApp_userHome

static function is_child ($me, $name)
{ // function is_child
global $store;
if (!strlen ($name) or $name[0] == '-')
return false;

if ($store->user->getId ($name))
return true;

return false;
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
$me->userId = $store->user->getId ($me->name);

$me->getMap ();

$me->groups = array ( new eclGroup_user ($me->userId));
$me->data = &$store->userContent->open ($me->userId, '-index');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->application = $document->application->child ('-index');
$document->pathway = $document->application->pathway;
$document->application->dispatch ($document);
} // function dispatch

} // class eclApp_userHome

?>