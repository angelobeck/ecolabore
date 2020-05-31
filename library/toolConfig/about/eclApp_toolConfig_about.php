<?php

class eclApp_toolConfig_about
{ // class eclApp_toolConfig_about

static function is_child ($parent, $name)
{ // function is_child
if ($name == 'about')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('about');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('toolConfig_about_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $store;
$me = $document->application;

$data = &$store->domainContent->open ($me->domainId, '-register');

$formulary = $document->createFormulary ('toolConfig_about_edit', $data);

if ($formulary->command ('save') and $formulary->save ())
{ // save
$data = $formulary->data;
} // save

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_toolConfig_about

?>