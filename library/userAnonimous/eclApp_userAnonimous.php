<?php

class eclApp_userAnonimous
{ // class eclApp_userAnonimous

static function is_child ($me, $name)
{ // function is_child
if ($name == '-anonimous')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'post';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-anonimous');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userAnonimous_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_userAnonimous

?>