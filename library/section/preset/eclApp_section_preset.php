<?php

class eclApp_section_preset
{ // class eclApp_section_preset

static function is_child ($me, $name)
{ // function is_child
if (!isset ($me->data['flags']['preset']))
return false;
if ($name == '-preset')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-preset');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;

$me->data = $store->control->read ($me->parent->data['flags']['preset']);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_section_preset

?>