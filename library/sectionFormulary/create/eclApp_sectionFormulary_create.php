<?php

class eclApp_sectionFormulary_create
{ // class eclApp_sectionFormulary_create

static function is_child ($me, $name)
{ // function is_child
if ($name == 'formulary')
return true;
if ($name == 'contact')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;

if ($store->domainContent->findMarker ($me->domainId, 4))
return array ('formulary');

return array ('contact', 'formulary');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionFormulary_create_' . $me->name);
$me->map = array ('section_preset');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_sectionFormulary_create

?>