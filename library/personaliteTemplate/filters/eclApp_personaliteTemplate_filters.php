<?php

class eclApp_personaliteTemplate_filters
{ // class eclApp_personaliteTemplate_filters

static function is_child ($me, $name)
{ // function is_child
if ($name == '-filters')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-filters');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteTemplate_filters_content');
$me->access = 4;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_personaliteTemplate_filters_list ($document);
} // function dispatch

} // class eclApp_personaliteTemplate_filters

?>