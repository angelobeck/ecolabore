<?php

class eclApp_personaliteTemplate_add
{ // class eclApp_personaliteTemplate_add

static function is_child ($me, $name)
{ // function is_child
switch ($name)
{ // switch name
case 'details':
case 'fields':
case 'fonts':
case 'layouts':
case 'lists':
case 'icons':
case 'modules':
case 'palettes':
case 'scripts':
case 'styles':
case 'themes':
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
$me->map = array ('personaliteTemplate_configure');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_personaliteTemplate_add_list ($document);
} // function dispatch

} // class eclApp_personaliteTemplate_add

?>