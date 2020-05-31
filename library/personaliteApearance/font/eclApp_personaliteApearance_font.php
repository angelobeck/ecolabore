<?php

class eclApp_personaliteApearance_font
{ // class eclApp_personaliteApearance_font

static function is_child ($me, $name)
{ // function is_child
if ($name == 'font')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('font');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteApearance_font_layout');
$me->access = 0;
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_personaliteApearance_font_list ($document);
} // function dispatch

} // class eclApp_personaliteApearance_font

?>