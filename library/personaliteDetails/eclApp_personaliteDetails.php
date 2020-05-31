<?php

class eclApp_personaliteDetails
{ // class eclApp_personaliteDetails

static function is_child ($me, $name)
{ // function is_child
if ($name == 'details')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('details');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteDetails_main');
$me->map = array ('personaliteDetails_configure');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_personaliteDetails_list ($document);
} // function dispatch

} // class eclApp_personaliteDetails

?>