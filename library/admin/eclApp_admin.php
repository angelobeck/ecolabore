<?php

class eclApp_admin
{ // class eclApp_admin

const menuType = 'domain';

static function is_child ($parent, $name)
{ // function is_child
if ($name == SYSTEM_ADMIN_URI or $name == '-' . SYSTEM_ADMIN_URI)
return true;

return false;
} // function is_child

static function get_children_names ()
{ // function get_children_names
return array (SYSTEM_ADMIN_URI);
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('admin_content');
$me->getMap ();
$me->isDomain = true;
} // function constructor_helper

} // class eclApp_admin

?>