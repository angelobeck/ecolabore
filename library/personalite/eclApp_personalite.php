<?php

class eclApp_personalite
{ // class eclApp_personalite

static function is_child ($me, $name)
{ // function is_child
if ($name == '-personalite')
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-personalite');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personalite_content');
$me->access = 4;
$me->getMap ();
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_sectionFolder_list ($document);
} // function dispatch

} // class eclApp_personalite

?>