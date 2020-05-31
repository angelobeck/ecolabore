<?php

class eclApp_sectionBlog_create
{ // class eclApp_sectionBlog_create

static function is_child ($me, $name)
{ // function is_child
switch ($name)
{ // switch name
case 'blog':
case 'galery':
case 'events':
case 'news':
case 'podcast':
case 'products':
case 'videocast':
return true;
} // switch name

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
global $store;
$names = array ('blog', 'galery', 'news', 'events', 'podcast', 'videocast');
if ($store->domainContent->findMarker ($me->domainId, 12))
$names[] = 'products';

return $names;
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('sectionBlog_create_' . $me->name);
$me->map = array ('section_preset');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
} // function dispatch

} // class eclApp_sectionBlog_create

?>