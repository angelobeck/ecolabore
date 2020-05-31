<?php

class eclApp_personaliteTemplate_labels
{ // class eclApp_personaliteTemplate_labels

static function is_child ($me, $name)
{ // function is_child
if ($name == 'labels')
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
$me->data = $store->control->read ('personaliteTemplate_add_content');
$me->map = array ('personaliteTemplate_labels_add');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_personaliteTemplate_labels

?>