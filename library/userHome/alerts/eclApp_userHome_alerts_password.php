<?php

class eclApp_userHome_alerts_password
{ // class eclApp_userHome_alerts_password

static function is_child ($me, $name)
{ // function is_child
if ($name == '-password-update')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (isset ($me->parent->data['local']['passwordLastUpdate']))
{ // last remember
if ( $me->parent->data['local']['passwordLastUpdate'] + 15552000 > TIME)
return [];

return ['-password-update'];
} // last remember

if (!isset ($me->parent->data['created']) or $me->parent->data['created'] + 15552000 > TIME)
return [];

return ['-password-update'];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userHome_alerts_password');
$me->pathway = array_slice ($me->pathway, 0,  - 2);
$me->pathway[] = $me->name;
$me->isDomain = true;
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_userHome_alerts_password

?>