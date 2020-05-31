<?php

class eclApp_adminSystem_pack
{ // class eclApp_adminSystem_pack

static function is_child ($me, $name)
{ // function is_child
if (defined ('SYSTEM_IS_PACKED') and SYSTEM_IS_PACKED)
return false;
if ($name == 'pack')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
if (!defined ('SYSTEM_IS_PACKED') or !SYSTEM_IS_PACKED)
return array ('pack');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('adminSystem_pack_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

$formulary = $document->createFormulary ('adminSystem_pack_edit');
if ($formulary->command ('pack') and $formulary->save ())
{ // pack file

$io->packager->pack ($formulary->data);

if (isset ($formulary->data['pack_mode']) and $formulary->data['pack_mode'] == 'replace')
{ // replace
$document->reload = $document->url ();
return;
} // replace
$document->mod->humperstilshen->alert ('adminSystem_pack_alertFinished');
} // pack file
$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_adminSystem_pack

?>