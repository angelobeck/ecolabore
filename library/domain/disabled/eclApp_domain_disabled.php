<?php

class eclApp_domain_disabled
{ // class eclApp_domain_disabled

static function is_child ($me, $name)
{ // function is_child
return true;
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
$me->name = '';
$me->data = $store->control->read ('domain_disabled_content');
$me->ignoreSubfolders = true;
array_pop ($me->pathway);
} // function constructor_helper

} // class eclApp_domain_disabled

?>