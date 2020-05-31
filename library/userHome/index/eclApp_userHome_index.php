<?php

class eclApp_userHome_index
{ // class eclApp_userHome_index

static function is_child ($me, $name)
{ // function is_child
if ($name == '-index')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-index');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('userHome_index_content');

if (isset ($me->parent->data['text']['caption']))
$me->data['text']['profile'] = $me->parent->data['text']['caption'];
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->list1 = new eclMod_userHome_index_domains ($document);
$document->mod->list2 = new eclMod_userHome_index_recents ($document);

$document->mod->panel->main = array ('description', 'content', 'list1', 'list2');
} // function dispatch

} // class eclApp_userHome_index

?>