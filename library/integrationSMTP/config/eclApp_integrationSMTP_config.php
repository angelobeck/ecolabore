<?php

class eclApp_integrationSMTP_config
{ // class eclApp_integrationSMTP_config

static function is_child ($me, $name)
{ // function is_child
if ($name == 'config')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
return array ('config');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('integrationSMTP_config_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

$formulary = $document->createFormulary ('integrationSMTP_config_edit');

if ($formulary->save ())
{ // formulary saved
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');
} // formulary saved

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_integrationSMTP_config

?>