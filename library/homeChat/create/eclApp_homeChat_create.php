<?php

class eclApp_homeChat_create
{ // class eclApp_homeChat_create

static function is_child ($me, $name)
{ // function is_child
global $store;
if (!$store->userContent->findMarker ($me->userId, 3) and $name == 'chat')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
if (!$store->userContent->findMarker ($me->userId, 3))
return array ('chat');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('homeChat_create_content');
$me->map = array ('home_preset');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_homeChat_create

?>