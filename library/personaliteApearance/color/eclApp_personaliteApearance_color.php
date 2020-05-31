<?php

class eclApp_personaliteApearance_color
{ // class eclApp_personaliteApearance_color

static function is_child ($me, $name)
{ // function is_child
if ($name == 'color')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('color');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteApearance_color_content');
$me->access = 0;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_personaliteApearance_color

?>