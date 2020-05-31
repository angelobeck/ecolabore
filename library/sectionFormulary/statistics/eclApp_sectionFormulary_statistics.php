<?php

class eclApp_sectionFormulary_statistics
{ // class eclApp_sectionFormulary_statistics

static function is_child ($me, $name)
{ // function is_child
if ($name == '-statistics')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
if (!isset ($me->data['extras']['formulary']))
return [];

foreach ($me->data['extras']['formulary'] as $name => $field)
{ // each field
list ($filter) = explode ('_', $name);
if ($filter == 'radio' or $filter == 'checkbox' or $filter == 'select')
return array ('-statistics');
} // each field

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->access = 4;
$me->data = $store->control->read ('sectionFormulary_statistics_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$document->mod->list = new eclMod_sectionFormulary_statistics_list ($document);
} // function dispatch

} // class eclApp_sectionFormulary_statistics

?>