<?php

class eclApp_toolConfig_languages_currencies
{ // class eclApp_toolConfig_languages_currencies

static function is_child ($parent, $name)
{ // function is_child
if ($name == 'currencies')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('currencies');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('toolConfig_languages_currencies');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_toolConfig_languages_currencies ($document);
} // function dispatch

} // class eclApp_toolConfig_languages_currencies

?>