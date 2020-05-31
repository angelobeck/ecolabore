<?php

class eclApp_userIndex
{ // class eclApp_userIndex

static function is_child ($me, $name)
{ // function is_child
if ($name == '')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userIndex_content');
unset ($me->pathway[1]);
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_userIndex_list ($document);
} // function dispatch

} // class eclApp_userIndex

?>