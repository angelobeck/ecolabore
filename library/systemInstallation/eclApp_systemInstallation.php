<?php

class eclApp_systemInstallation
{ // class eclApp_systemInstallation

static function is_child ($parent, $name)
{ // function is_child
if (!defined ('ADMIN_IDENTIFIER'))
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'domain';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('systemInstallation_index');
$me->map = array ('systemInstallation_index');
$me->isDomain = true;
} // function constructor_helper

} // class eclApp_systemInstallation

?>