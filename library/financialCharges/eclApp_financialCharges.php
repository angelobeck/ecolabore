<?php

class eclApp_financialCharges
{ // class eclApp_financialCharges

static function is_child ($parent, $name)
{ // function is_child
if ($name == 'charges')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('charges');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('financialCharges_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$document->mod->list = new eclMod_financialCharges_list ($document);
} // function dispatch

} // class eclApp_financialCharges

?>