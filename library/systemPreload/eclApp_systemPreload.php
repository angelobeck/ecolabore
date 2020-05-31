<?php

class eclApp_systemPreload
{ // class eclApp_systemPreload

static function is_child ($me, $name)
{ // function is_child
if ($name == '-preload')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-preload');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
$me->getMap ();
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_systemPreload

?>