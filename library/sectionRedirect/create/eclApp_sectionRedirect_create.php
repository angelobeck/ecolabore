<?php

class eclApp_sectionRedirect_create
{ // class eclApp_sectionRedirect_create

static function is_child ($me, $name)
{ // function is_child
global $store;
if ($name == 'redirect')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
return array ('redirect');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionRedirect_create_content');
$me->map = array ('section_preset');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_sectionRedirect_create

?>