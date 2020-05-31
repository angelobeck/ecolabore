<?php

class eclApp_adminSystem_extract
{ // class eclApp_adminSystem_extract

static function is_child ($me, $name)
{ // function is_child
if (defined ('SYSTEM_IS_PACKED') and SYSTEM_IS_PACKED and $name == 'pack')
return true;

return false;
} // function is_child

static function get_menu_type ()
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ()
{ // function get_children_names
if (defined ('SYSTEM_IS_PACKED') and SYSTEM_IS_PACKED)
return array ('pack');

return [];
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;

$me->data = $store->control->read ('adminSystem_extract_content');
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io;

$formulary = $document->createFormulary ('adminSystem_extract_edit');

if ($formulary->command ('save') and $formulary->save ())
{ // options saved
$document->mod->humperstilshen->alert ('system_msg_alertDataUpdated');
} // options saved
elseif ($formulary->command ('extract') and $formulary->save ())
{ // extract files

$io->packager->extract ($formulary->data);

$document->reload = $document->url ();
return;
} // extract files

$document->mod->formulary = $formulary;
if ($formulary->errorMsg)
$document->mod->humperstilshen->alert ($formulary->errorMsg);
} // function dispatch

} // class eclApp_adminSystem_extract

?>