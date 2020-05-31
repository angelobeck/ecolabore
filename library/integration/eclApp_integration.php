<?php

class eclApp_integration
{ // class eclApp_integration

static function is_child ($parent, $name)
{ // function is_child
if ($name == 'integration')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('integration');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('integration_content');
$me->access = 4;
$me->getMap ();
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_integration

?>