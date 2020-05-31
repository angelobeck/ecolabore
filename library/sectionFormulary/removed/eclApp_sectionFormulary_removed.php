<?php

class eclApp_sectionFormulary_removed
{ // class eclApp_sectionFormulary_removed

static function is_child ($me, $name)
{ // function is_child
if ($name == '-removed')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
$children = $store->domainExtras->children ($me->domainId, MODE_FORM, $me->parent->id);
foreach ($children as $data)
{ // each children
if ($data['status'] >= 730)
return array ('-removed');
} // each data

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->access = 4;
$me->data = $store->control->read ('sectionFormulary_removed_content');
$me->map = array ('sectionFormulary_record');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$children = $store->domainExtras->children ($me->domainId, MODE_FORM, $me->parent->id);

$document->mod->list = new eclMod_sectionFormulary_removed_list ($document);
} // function dispatch

} // class eclApp_sectionFormulary_removed

?>