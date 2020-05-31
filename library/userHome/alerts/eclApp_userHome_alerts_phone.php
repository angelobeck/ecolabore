<?php

class eclApp_userHome_alerts_phone
{ // class eclApp_userHome_alerts_phone

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
global $store;
if (!INTEGRATION_SMS_ENABLE)
return [];

$name = $me->pathway[1];
$user = $store->user->open ($name);
if (!$user['phone'])
return array ('-phone');
else
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userHome_alerts_phone');
$me->pathway = array_slice ($me->pathway, 0,  - 2);
$me->pathway[] = $me->name;
$me->isDomain = true;
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_userHome_alerts_phone

?>