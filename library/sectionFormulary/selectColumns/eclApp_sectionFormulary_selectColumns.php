<?php

class eclApp_sectionFormulary_selectColumns
{ // class eclApp_sectionFormulary_selectColumns

static function is_child ($me, $name)
{ // function is_child
if ($name == '-columns')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('-columns');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionFormulary_selectColumns_content');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list = new eclMod_sectionFormulary_selectColumns_list ($document);
} // function dispatch

} // class eclApp_sectionFormulary_selectColumns

?>