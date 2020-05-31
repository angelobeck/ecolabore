<?php

class eclApp_personaliteExport
{ // class eclApp_personaliteExport

static function is_child ($me, $name)
{ // function is_child
if ($name == 'export')
return true;

return false;
} // function is_child

static function get_menu_type ($me)
{ // function get_menu_type
return 'section';
} // function get_menu_type

static function get_children_names ($me)
{ // function get_children_names
return array ('export');
} // function get_children_names

static function constructor_helper ($me)
{ // function constructor_helper
global $store;
$me->data = $store->control->read ('personaliteExport_content');
$me->isDomain = true;
} // function constructor_helper

static function dispatch ($document)
{ // function dispatch
global $io, $store;

$data['ecolabore-template'] = '1.0';
$data['created'] = date ('Y-m-d');
$data['name'] = 'ecolabore-template+' . date ('Y-m-d') . '.zip';
$formulary = $document->createFormulary ('personaliteExport_edit', $data, 'export');

if ($formulary->command ('save') and $formulary->save ())
{ // save and export
return;
} // save and export

$document->mod->formulary = $formulary;
} // function dispatch

} // class eclApp_personaliteExport

?>