<?php

class eclApp_financialProjects_course_class
{ // class eclApp_financialProjects_course_class

static function is_child ($me, $name)
{ // function is_child
global $store;
if ($store->domainContent->open ($me->domainId, $name))
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->domainContent->open ($me->domainId, $me->name);
$me->map = [];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_admin_list ($document);
} // function dispatch

} // class eclApp_financialProjects_course_class

?>