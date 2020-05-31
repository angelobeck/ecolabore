<?php

class eclApp_tool
{ // class eclApp_tool

static function is_child ($parent, $name)
{ // function is_child
if ($name == '-tools')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-tools');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('tool_content');
$me->access = 4;
$me->getMap ();
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_tool

?>