<?php

class eclApp_sectionMenu_create
{ // class eclApp_sectionMenu_create

static function is_child ($me, $name)
{ // function is_child
if (count ($me->pathway) < 3 and $name == 'menu')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (count ($me->pathway) < 3)
return array ('menu');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionMenu_create_menu');
$me->map = array ('section_preset');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_sectionMenu_create

?>