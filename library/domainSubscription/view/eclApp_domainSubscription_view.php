<?php

class eclApp_domainSubscription_view
{ // class eclApp_domainSubscription_view

static function is_child ($me, $name)
{ // function is_child
return true;
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
global $store;
if (substr ($me->name, 0, 6) == '-user-')
$me->name = substr ($me->name, 6);
$me->data = &$store->domainContent->open ($me->domainId, '-user-' . $me->name);
if (isset ($me->data['id']))
$me->id = $me->data['id'];
$me->access = 4;
$me->map = array ('domainSubscription_task');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_domainSubscription_view

?>