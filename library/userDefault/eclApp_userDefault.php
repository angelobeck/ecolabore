<?php

class eclApp_userDefault
{ // class eclApp_userDefault

static function is_child ($me, $name)
{ // function is_child
if ($name == '-default')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-default');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userDefault_content');
$me->ignoreSubfolders = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_userDefault

?>