<?php

class eclApp_systemDefault
{ // class eclApp_systemDefault

static function is_child ($me, $name)
{ // function is_child
if ($name == '-default')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'domain';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-default');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('systemDefault_content');
$me->ignoreSubfolders = true;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->base = 'system_message';
} // function dispatch

} // class eclApp_systemDefault

?>