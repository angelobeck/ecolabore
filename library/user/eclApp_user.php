<?php

class eclApp_user
{ // class eclApp_user

static function is_child ($me, $name)
{ // function is_child
if ($name == SYSTEM_PROFILES_URI)
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'domain';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array (SYSTEM_PROFILES_URI);
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('user_content');
$me->groups = [];
$me->getMap ();
$me->isDomain = true;
} // function constructor_helper

} // class eclApp_user

?>