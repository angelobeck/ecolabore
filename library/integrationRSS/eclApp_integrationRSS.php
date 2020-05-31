<?php

class eclApp_integrationRSS
{ // class eclApp_integrationRSS

static function is_child ($me, $name)
{ // function is_child
if ($name == 'rss')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('rss');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store, $system;
$me->data = $store->control->read ('integrationRSS_content');
$me->access = 4;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$formulary = $document->createFormulary ('integrationRSS_edit', $document->domain->data, 'rss');

if ($formulary->command ('save') and $formulary->save ())
{ // save
$document->domain->data = $formulary->data;
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');
} // save

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_integrationRSS

?>