<?php

class eclApp_sectionInfo_create
{ // class eclApp_sectionInfo_create

static function is_child ($me, $name)
{ // function is_child
global $store;

if ($me->parent->access)
return false;

if (!$store->domainContent->findMarker ($me->domainId, 6) and $name == 'info')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($parent)
{ // function get_children_names
global $store;

if ($parent->parent->access)
return [];

if (!$store->domainContent->findMarker ($parent->domainId, 6))
return array ('info');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionInfo_create_content');
$me->map = array ('section_preset');
} // function constructor_helper

} // class eclApp_sectionInfo_create

?>