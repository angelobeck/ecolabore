<?php

class eclApp_userAdmin_index
{ // class eclApp_userAdmin_index

static function is_child ($me, $name)
{ // function is_child
if ($name == '' or $name == '-index')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userAdmin_content');
array_pop ($me->pathway);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_userAdmin_index

?>