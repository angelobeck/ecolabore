<?php

class eclApp_personaliteTemplate_labels_add
{ // class eclApp_personaliteTemplate_labels_add

static function is_child ($me, $name)
{ // function is_child
switch ($name)
{ // switch name
case 'action':
case 'date':
case 'field':
case 'lang':
case 'navigation':
return true;

default:
return false;
} // switch name
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
$me->map = array ('personaliteTemplate_labels_configure');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_personaliteTemplate_labels_add ($document);
} // function dispatch

} // class eclApp_personaliteTemplate_labels_add

?>