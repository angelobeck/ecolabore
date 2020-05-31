<?php

class eclApp_domainSearch
{ // class eclApp_domainSearch

static function is_child ($me, $name)
{ // function is_child
if ($name == '-search')
return true;
return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'hidden';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('-search');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('domainSearch_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
$document->mod->panel->main = array ('content', 'formulary', 'list');
$document->mod->formulary = new eclMod_domainSearch_formulary ($document);
$document->mod->list = new eclMod_domainSearch_list ($document);
} // function dispatch

} // class eclApp_domainSearch

?>